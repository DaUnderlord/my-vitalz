<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Cookie;

/**
 * Affiliate Controller
 * 
 * Manages affiliate network building and tracking
 * 
 * Features:
 * - Generate unique affiliate links
 * - Track link clicks and conversions
 * - View link statistics and performance
 * - Manage link activation/deactivation
 * - Generate custom invitation messages
 * 
 * @author MyVitalz Development Team
 * @version 1.0.0
 * @date 2025-11-01
 */
class AffiliateController extends Controller
{
    /**
     * Sanitize input to prevent XSS attacks
     * 
     * @param mixed $input
     * @return mixed
     */
    private function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check user authentication
     * 
     * @return object User object
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
     * Get user type (doctor, pharmacy, hospital, patient)
     * 
     * @param object $user
     * @return string
     */
    private function getUserType($user)
    {
        if ($user->doctor == 1) return 'doctor';
        if ($user->pharmacy == 1) return 'pharmacy';
        if ($user->hospital == 1) return 'hospital';
        return 'patient';
    }
    
    /**
     * Generate unique link code
     * 
     * @return string
     */
    private function generateLinkCode()
    {
        do {
            // Generate code: MV + 8 random alphanumeric characters
            $code = 'MV' . strtoupper(Str::random(8));
            $exists = DB::select('select id from affiliate_links WHERE link_code=?', [$code]);
        } while (!empty($exists));
        
        return $code;
    }
    
    /**
     * Generate affiliate link
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|view
     */
    public function generateLink(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        $providerType = $this->getUserType($user);
        
        // Only doctors, pharmacies, and hospitals can generate links
        if (!in_array($providerType, ['doctor', 'pharmacy', 'hospital'])) {
            return redirect()->back()->with('error', 'Only healthcare providers can generate affiliate links');
        }
        
        if ($request->isMethod('post')) {
            $targetType = $this->sanitizeInput($request->input('target_type'));
            $customMessage = $this->sanitizeInput($request->input('custom_message', ''));
            
            // Validate target type
            if (!in_array($targetType, ['patient', 'doctor', 'pharmacy', 'hospital'])) {
                return redirect()->back()->with('error', 'Invalid target type');
            }
            
            // Generate unique code
            $linkCode = $this->generateLinkCode();
            
            // Build URL based on target type
            $signupUrl = url('/signup-' . $targetType);
            $linkUrl = $signupUrl . '?ref=' . $linkCode;
            
            // Insert into database
            try {
                DB::insert('insert into affiliate_links (provider_id, provider_type, link_code, link_url, target_type, custom_message, is_active, created_at, updated_at) values (?,?,?,?,?,?,?,?,?)', [
                    $uid,
                    $providerType,
                    $linkCode,
                    $linkUrl,
                    $targetType,
                    $customMessage,
                    1,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                ]);
                
                // Update user's last network activity
                DB::update('update users set last_network_activity=? WHERE id=?', [date('Y-m-d H:i:s'), $uid]);
                
                return redirect()->back()->with([
                    'success' => 'Affiliate link generated successfully!',
                    'link_url' => $linkUrl,
                    'link_code' => $linkCode
                ]);
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to generate link. Please try again.');
            }
        }
        
        // GET request - show form
        return $this->viewLinks($request);
    }
    
    /**
     * View all affiliate links for current user
     * 
     * @param Request $request
     * @return view
     */
    public function viewLinks(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        // Get all links for this provider
        $links = DB::select('
            SELECT * FROM affiliate_links 
            WHERE provider_id = ? 
            ORDER BY created_at DESC
        ', [$uid]);
        
        // Calculate totals
        $totalClicks = 0;
        $totalRegistrations = 0;
        $totalActiveUsers = 0;
        
        foreach ($links as $link) {
            $totalClicks += $link->clicks;
            $totalRegistrations += $link->registrations;
            $totalActiveUsers += $link->active_users;
        }
        
        return view('affiliate.links', [
            'user' => $user,
            'links' => $links,
            'total_clicks' => $totalClicks,
            'total_registrations' => $totalRegistrations,
            'total_active_users' => $totalActiveUsers
        ]);
    }
    
    /**
     * Track link click
     * 
     * @param string $linkCode
     * @return void
     */
    public function trackClick($linkCode)
    {
        // Increment click counter
        DB::update('
            UPDATE affiliate_links 
            SET clicks = clicks + 1, 
                last_used_at = ? 
            WHERE link_code = ?
        ', [date('Y-m-d H:i:s'), $linkCode]);
    }
    
    /**
     * Toggle link activation status
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleLink(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $linkId = $this->sanitizeInput($request->input('link_id'));
        
        // Verify ownership
        $link = DB::select('select * from affiliate_links WHERE id=? AND provider_id=?', [$linkId, $uid]);
        
        if (empty($link)) {
            return redirect()->back()->with('error', 'Link not found');
        }
        
        // Toggle status
        $newStatus = $link[0]->is_active == 1 ? 0 : 1;
        
        DB::update('update affiliate_links set is_active=?, updated_at=? WHERE id=?', [
            $newStatus,
            date('Y-m-d H:i:s'),
            $linkId
        ]);
        
        $message = $newStatus == 1 ? 'Link activated successfully' : 'Link deactivated successfully';
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Delete affiliate link
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteLink(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $linkId = $this->sanitizeInput($request->input('link_id'));
        
        // Verify ownership
        $link = DB::select('select * from affiliate_links WHERE id=? AND provider_id=?', [$linkId, $uid]);
        
        if (empty($link)) {
            return redirect()->back()->with('error', 'Link not found');
        }
        
        // Check if link has registrations
        if ($link[0]->registrations > 0) {
            return redirect()->back()->with('error', 'Cannot delete link with existing registrations. Deactivate it instead.');
        }
        
        // Delete link
        DB::delete('delete from affiliate_links WHERE id=?', [$linkId]);
        
        return redirect()->back()->with('success', 'Link deleted successfully');
    }
    
    /**
     * Get link statistics
     * 
     * @param Request $request
     * @return view
     */
    public function viewStats(Request $request)
    {
        $user = $this->checkAuth();
        $uid = $user->id;
        
        $linkId = $this->sanitizeInput($request->input('link_id'));
        
        // Get link details
        $link = DB::select('select * from affiliate_links WHERE id=? AND provider_id=?', [$linkId, $uid]);
        
        if (empty($link)) {
            return redirect()->back()->with('error', 'Link not found');
        }
        
        $link = $link[0];
        
        // Get users who registered through this link
        $users = DB::select('
            SELECT u.*, al.link_code 
            FROM users u
            INNER JOIN affiliate_links al ON u.affiliate_provider_id = al.provider_id
            WHERE al.id = ? AND u.registration_source LIKE ?
            ORDER BY u.created_at DESC
        ', [$linkId, '%_link']);
        
        // Calculate conversion rate
        $conversionRate = $link->clicks > 0 ? ($link->registrations / $link->clicks) * 100 : 0;
        
        // Calculate retention rate
        $retentionRate = $link->registrations > 0 ? ($link->active_users / $link->registrations) * 100 : 0;
        
        return view('affiliate.stats', [
            'user' => $user,
            'link' => $link,
            'users' => $users,
            'conversion_rate' => round($conversionRate, 2),
            'retention_rate' => round($retentionRate, 2)
        ]);
    }
    
    /**
     * Get invitation message template
     * 
     * @param string $providerType
     * @param string $targetType
     * @param object $user
     * @return string
     */
    public function getMessageTemplate($providerType, $targetType, $user)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $city = $user->city ?? 'your area';
        $specialization = $user->specialization ?? 'healthcare professional';
        
        $templates = [
            'doctor' => [
                'patient' => "Hello! This is Dr {$name} from {$city}. It is my pleasure to inform you that I now monitor and manage my patients @Virtual Clinic from MyVitalz. If you would like me to monitor your blood pressure, blood glucose and other vital signs, you can reach me easily by joining my network when you click the link below to register.",
                
                'pharmacy' => "Hello Triage Pharmacy. This is Dr {$name}, a {$specialization} from {$city}. It is my pleasure to invite you to join my network on MyVitalz so that I can patronize your store and also help you manage your patients when you need me. You can click the link below to connect with me.",
            ],
            
            'pharmacy' => [
                'patient' => "Hello! This is {$name} from {$city}. We are inviting you to join our network on Virtual Clinic to help you monitor your vitals and replenish your routine drugs. Click the link below to get started.",
                
                'doctor' => "Hi Dr {$name}, this is {$name} in {$city}. We are seeking to join your network so we can refer patients to you or you can buy drugs from us. Please click the link below so we can connect.",
            ],
            
            'hospital' => [
                'patient' => "Hello! This is {$name} in {$city}. We are inviting you as our patient to join our network on Virtual Clinic to monitor your vitals. You may click the link below to register and get started for your future appointments and consultations.",
                
                'doctor' => "Hi Dr {$name}, we are a hospital seeking to connect with you for clinical collaboration. Can we join your network? Click the link below to connect.",
            ]
        ];
        
        return $templates[$providerType][$targetType] ?? "Join my network on MyVitalz! Click the link below to register.";
    }
    
    /**
     * Copy link to clipboard (returns JSON for AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyLink(Request $request)
    {
        $linkId = $this->sanitizeInput($request->input('link_id'));
        
        $link = DB::select('select link_url from affiliate_links WHERE id=?', [$linkId]);
        
        if (empty($link)) {
            return response()->json(['success' => false, 'message' => 'Link not found']);
        }
        
        return response()->json([
            'success' => true,
            'link_url' => $link[0]->link_url
        ]);
    }
}
