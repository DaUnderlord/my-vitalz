<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Cookie;

class NetworkController extends Controller
{
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check authentication
     */
    private function checkAuth()
    {
        if (Cookie::get('uid') == "") {
            redirect()->to("/")->send();
            exit();
        }
        
        $uid = Cookie::get('uid');
        $user = DB::select('select * from users WHERE id=?', [$uid]);
        
        if (empty($user)) {
            redirect()->to("/")->send();
            exit();
        }
        
        return $user[0];
    }
    
    /**
     * Generate unique invitation code
     */
    private function generateInvitationCode()
    {
        do {
            $code = strtoupper(Str::random(12));
            $exists = DB::select('select id from network_invitations WHERE invitation_code=?', [$code]);
        } while (!empty($exists));
        
        return $code;
    }
    
    /**
     * Get user type (doctor, hospital, pharmacy, patient)
     */
    private function getUserType($user)
    {
        if ($user->doctor == 1) return 'doctor';
        if ($user->hospital == 1) return 'hospital';
        if ($user->pharmacy == 1) return 'pharmacy';
        return 'patient';
    }
    
    /**
     * Send network invitation
     */
    public function sendInvitation(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        $senderType = $this->getUserType($user);
        
        // Get invitation details
        $receiverEmail = $this->sanitizeInput($request->input('receiver_email'));
        $receiverPhone = $this->sanitizeInput($request->input('receiver_phone'));
        $receiverType = $this->sanitizeInput($request->input('receiver_type'));
        $message = $this->sanitizeInput($request->input('message', ''));
        
        // Validate receiver type
        if (!in_array($receiverType, ['doctor', 'hospital', 'pharmacy', 'patient'])) {
            return redirect()->back()->with('error', 'Invalid receiver type');
        }
        
        // Check if receiver exists in system
        $receiverId = null;
        if ($receiverEmail) {
            $receiver = DB::select('select id from users WHERE email=?', [$receiverEmail]);
            if (!empty($receiver)) {
                $receiverId = $receiver[0]->id;
            }
        } elseif ($receiverPhone) {
            $receiver = DB::select('select id from users WHERE phone=?', [$receiverPhone]);
            if (!empty($receiver)) {
                $receiverId = $receiver[0]->id;
            }
        }
        
        // Check if already in network
        if ($receiverId) {
            $existing = DB::select('select id from network_members WHERE network_owner_id=? AND member_id=?', [$uid, $receiverId]);
            if (!empty($existing)) {
                return redirect()->back()->with('error', 'User is already in your network');
            }
            
            // Check for pending invitation
            $pendingInvite = DB::select('select id from network_invitations WHERE sender_id=? AND receiver_id=? AND status=?', [$uid, $receiverId, 'pending']);
            if (!empty($pendingInvite)) {
                return redirect()->back()->with('error', 'Invitation already sent to this user');
            }
        }
        
        // Generate invitation code
        $invitationCode = $this->generateInvitationCode();
        
        // Set expiry (30 days from now)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        // Create invitation
        DB::insert('insert into network_invitations (sender_id, sender_type, receiver_id, receiver_email, receiver_phone, receiver_type, invitation_code, message, status, expires_at, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?)', [
            $uid,
            $senderType,
            $receiverId,
            $receiverEmail,
            $receiverPhone,
            $receiverType,
            $invitationCode,
            $message,
            'pending',
            $expiresAt,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        // Log activity
        $this->logActivity($uid, 'invite_sent', $receiverId, $receiverType, "Sent network invitation to " . ($receiverEmail ?? $receiverPhone));
        
        // Send notification/email (implement later)
        if ($receiverId) {
            $this->sendInvitationNotification($receiverId, $user, $invitationCode);
        }
        
        return redirect()->back()->with('success', 'Invitation sent successfully! Code: ' . $invitationCode);
    }
    
    /**
     * View all invitations (sent and received)
     */
    public function viewInvitations(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        // Get sent invitations
        $sentInvitations = DB::select('
            SELECT ni.*, u.first_name, u.last_name, u.email, u.phone
            FROM network_invitations ni
            LEFT JOIN users u ON ni.receiver_id = u.id
            WHERE ni.sender_id = ?
            ORDER BY ni.created_at DESC
        ', [$uid]);
        
        // Get received invitations
        $receivedInvitations = DB::select('
            SELECT ni.*, u.first_name, u.last_name, u.email, u.phone
            FROM network_invitations ni
            LEFT JOIN users u ON ni.sender_id = u.id
            WHERE ni.receiver_id = ? AND ni.status = ?
            ORDER BY ni.created_at DESC
        ', [$uid, 'pending']);
        
        return view('network.invitations', [
            'user' => $user,
            'sent_invitations' => $sentInvitations,
            'received_invitations' => $receivedInvitations
        ]);
    }
    
    /**
     * Accept network invitation
     */
    public function acceptInvitation(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $invitationId = $this->sanitizeInput($request->input('invitation_id'));
        
        // Get invitation
        $invitation = DB::select('select * from network_invitations WHERE id=? AND receiver_id=? AND status=?', [$invitationId, $uid, 'pending']);
        
        if (empty($invitation)) {
            return redirect()->back()->with('error', 'Invitation not found or already processed');
        }
        
        $invitation = $invitation[0];
        
        // Check if expired
        if (strtotime($invitation->expires_at) < time()) {
            DB::update('update network_invitations set status=?, updated_at=? WHERE id=?', ['expired', date('Y-m-d H:i:s'), $invitationId]);
            return redirect()->back()->with('error', 'Invitation has expired');
        }
        
        // Update invitation status
        DB::update('update network_invitations set status=?, accepted_at=?, updated_at=? WHERE id=?', ['accepted', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $invitationId]);
        
        // Add to network members
        $memberType = $this->getUserType($user);
        
        DB::insert('insert into network_members (network_owner_id, network_owner_type, member_id, member_type, status, joined_at, created_at, updated_at) values (?,?,?,?,?,?,?,?)', [
            $invitation->sender_id,
            $invitation->sender_type,
            $uid,
            $memberType,
            'active',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        // Update network size cache
        DB::update('update users set network_size = network_size + 1, last_network_activity=? WHERE id=?', [date('Y-m-d H:i:s'), $invitation->sender_id]);
        
        // Log activity
        $this->logActivity($uid, 'invite_accepted', $invitation->sender_id, $invitation->sender_type, "Accepted network invitation");
        $this->logActivity($invitation->sender_id, 'member_added', $uid, $memberType, "New member added to network");
        
        // Send notification to sender
        $this->sendAcceptanceNotification($invitation->sender_id, $user);
        
        return redirect()->back()->with('success', 'Invitation accepted! You are now part of the network.');
    }
    
    /**
     * Decline network invitation
     */
    public function declineInvitation(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $invitationId = $this->sanitizeInput($request->input('invitation_id'));
        
        // Get invitation
        $invitation = DB::select('select * from network_invitations WHERE id=? AND receiver_id=? AND status=?', [$invitationId, $uid, 'pending']);
        
        if (empty($invitation)) {
            return redirect()->back()->with('error', 'Invitation not found or already processed');
        }
        
        // Update invitation status
        DB::update('update network_invitations set status=?, declined_at=?, updated_at=? WHERE id=?', ['declined', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $invitationId]);
        
        // Log activity
        $this->logActivity($uid, 'invite_declined', $invitation[0]->sender_id, $invitation[0]->sender_type, "Declined network invitation");
        
        return redirect()->back()->with('success', 'Invitation declined');
    }
    
    /**
     * Remove member from network
     */
    public function removeMember(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $memberId = $this->sanitizeInput($request->input('member_id'));
        $reason = $this->sanitizeInput($request->input('reason', ''));
        
        // Check if member exists in network
        $member = DB::select('select * from network_members WHERE network_owner_id=? AND member_id=?', [$uid, $memberId]);
        
        if (empty($member)) {
            return redirect()->back()->with('error', 'Member not found in your network');
        }
        
        // Update member status to blocked
        DB::update('update network_members set status=?, blocked_at=?, block_reason=?, updated_at=? WHERE id=?', ['blocked', date('Y-m-d H:i:s'), $reason, date('Y-m-d H:i:s'), $member[0]->id]);
        
        // Update network size cache
        DB::update('update users set network_size = network_size - 1, last_network_activity=? WHERE id=?', [date('Y-m-d H:i:s'), $uid]);
        
        // Log activity
        $this->logActivity($uid, 'member_removed', $memberId, $member[0]->member_type, "Removed member from network: " . $reason);
        
        return redirect()->back()->with('success', 'Member removed from network');
    }
    
    /**
     * View network members
     */
    public function viewMembers(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        // Get all active network members
        $members = DB::select('
            SELECT nm.*, u.first_name, u.last_name, u.email, u.phone, u.photo, u.city, u.state, u.doctor, u.hospital, u.pharmacy
            FROM network_members nm
            LEFT JOIN users u ON nm.member_id = u.id
            WHERE nm.network_owner_id = ? AND nm.status = ?
            ORDER BY nm.joined_at DESC
        ', [$uid, 'active']);
        
        // Group by member type
        $doctors = [];
        $hospitals = [];
        $pharmacies = [];
        $patients = [];
        
        foreach ($members as $member) {
            if ($member->member_type == 'doctor') {
                $doctors[] = $member;
            } elseif ($member->member_type == 'hospital') {
                $hospitals[] = $member;
            } elseif ($member->member_type == 'pharmacy') {
                $pharmacies[] = $member;
            } else {
                $patients[] = $member;
            }
        }
        
        return view('network.members', [
            'user' => $user,
            'doctors' => $doctors,
            'hospitals' => $hospitals,
            'pharmacies' => $pharmacies,
            'patients' => $patients,
            'total_members' => count($members)
        ]);
    }
    
    /**
     * Toggle doctor public profile
     */
    public function togglePublicProfile(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        // Only doctors can toggle public profile
        if ($user->doctor != 1) {
            return redirect()->back()->with('error', 'Only doctors can toggle public profile');
        }
        
        $isPublic = $request->input('is_public') == '1' ? 1 : 0;
        
        // Update public profile status
        DB::update('update users set public_profile=?, updated_at=? WHERE id=?', [$isPublic, date('Y-m-d H:i:s'), $uid]);
        
        // Log activity
        $status = $isPublic ? 'public' : 'private';
        $this->logActivity($uid, 'profile_visibility_changed', null, null, "Changed profile visibility to " . $status);
        
        $message = $isPublic ? 'Your profile is now public and discoverable' : 'Your profile is now private';
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Log network activity
     */
    private function logActivity($userId, $actionType, $targetUserId, $targetUserType, $description)
    {
        $metadata = json_encode([
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s')
        ]);
        
        DB::insert('insert into network_activity_log (user_id, action_type, target_user_id, target_user_type, description, metadata, ip_address, user_agent, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?)', [
            $userId,
            $actionType,
            $targetUserId,
            $targetUserType,
            $description,
            $metadata,
            request()->ip(),
            request()->userAgent(),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Send invitation notification
     */
    private function sendInvitationNotification($receiverId, $sender, $invitationCode)
    {
        $title = 'Network Invitation';
        $message = $sender->first_name . ' ' . $sender->last_name . ' has invited you to join their network. Code: ' . $invitationCode;
        
        DB::insert('INSERT INTO notifications(user_id, title, message, date, created_at, updated_at) VALUES (?,?,?,?,?,?)', [
            $receiverId,
            $title,
            $message,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Send acceptance notification
     */
    private function sendAcceptanceNotification($senderId, $accepter)
    {
        $title = 'Invitation Accepted';
        $message = $accepter->first_name . ' ' . $accepter->last_name . ' has accepted your network invitation!';
        
        DB::insert('INSERT INTO notifications(user_id, title, message, date, created_at, updated_at) VALUES (?,?,?,?,?,?)', [
            $senderId,
            $title,
            $message,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }
}
