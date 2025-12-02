<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Network Helper
 * 
 * Helper functions for network isolation and affiliate tracking
 * 
 * @author MyVitalz Development Team
 * @version 1.0.0
 * @date 2025-11-01
 */
class NetworkHelper
{
    /**
     * Process affiliate registration
     * 
     * @param string $refCode Affiliate link code from URL
     * @param int $newUserId ID of newly registered user
     * @param string $userType Type of user (patient, doctor, pharmacy, hospital)
     * @return array Registration details
     */
    public static function processAffiliateRegistration($refCode, $newUserId, $userType)
    {
        // Look up affiliate link
        $link = DB::select('select * from affiliate_links WHERE link_code=? AND is_active=1', [$refCode]);
        
        if (empty($link)) {
            // Invalid or inactive link - treat as direct registration
            return [
                'is_affiliate' => false,
                'registration_source' => 'direct',
                'affiliate_provider_id' => null,
                'network_locked' => 0
            ];
        }
        
        $link = $link[0];
        $providerId = $link->provider_id;
        $providerType = $link->provider_type;
        
        // Update user with affiliate information
        DB::update('
            UPDATE users 
            SET registration_source = ?,
                affiliate_provider_id = ?,
                network_locked = 1,
                last_network_activity = ?
            WHERE id = ?
        ', [
            $providerType . '_link',
            $providerId,
            date('Y-m-d H:i:s'),
            $newUserId
        ]);
        
        // Create network relationship
        self::createNetworkRelationship($newUserId, $providerId, $providerType, $userType);
        
        // Update link statistics
        DB::update('
            UPDATE affiliate_links 
            SET registrations = registrations + 1,
                active_users = active_users + 1,
                last_used_at = ?
            WHERE id = ?
        ', [date('Y-m-d H:i:s'), $link->id]);
        
        // Update provider's patient count if applicable
        if ($userType == 'patient' && $providerType == 'doctor') {
            DB::update('
                UPDATE users 
                SET active_patients_count = active_patients_count + 1,
                    last_network_activity = ?
                WHERE id = ?
            ', [date('Y-m-d H:i:s'), $providerId]);
        }
        
        // Send notification to provider
        self::sendAffiliateNotification($providerId, $newUserId, $userType);
        
        return [
            'is_affiliate' => true,
            'registration_source' => $providerType . '_link',
            'affiliate_provider_id' => $providerId,
            'network_locked' => 1,
            'provider_type' => $providerType
        ];
    }
    
    /**
     * Create network relationship in patients table
     * 
     * @param int $userId User ID
     * @param int $providerId Provider ID
     * @param string $providerType Provider type (doctor, pharmacy, hospital)
     * @param string $userType User type (patient, doctor, pharmacy, hospital)
     * @return void
     */
    private static function createNetworkRelationship($userId, $providerId, $providerType, $userType)
    {
        // Check if relationship already exists
        $existing = DB::select('
            SELECT id FROM patients 
            WHERE user = ? AND ' . $providerType . ' = ?
        ', [$userId, $providerId]);
        
        if (!empty($existing)) {
            return; // Relationship already exists
        }
        
        // Create relationship based on provider type
        if ($providerType == 'doctor') {
            DB::insert('
                INSERT INTO patients (user, doctor, user_approve, doctor_approve, created_at, updated_at) 
                VALUES (?, ?, 1, 1, ?, ?)
            ', [$userId, $providerId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
        } elseif ($providerType == 'pharmacy') {
            DB::insert('
                INSERT INTO patients (user, pharmacy, user_approve, pharmacy_approve, created_at, updated_at) 
                VALUES (?, ?, 1, 1, ?, ?)
            ', [$userId, $providerId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
            // Also add to pharmacy_networks table
            DB::insert('
                INSERT INTO pharmacy_networks (pharmacy_id, member_id, member_type, status, joined_at, created_at, updated_at) 
                VALUES (?, ?, ?, "active", ?, ?, ?)
            ', [$providerId, $userId, $userType, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
        } elseif ($providerType == 'hospital') {
            DB::insert('
                INSERT INTO patients (user, hospital, user_approve, hospital_approve, created_at, updated_at) 
                VALUES (?, ?, 1, 1, ?, ?)
            ', [$userId, $providerId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        }
    }
    
    /**
     * Send notification to provider about new network member
     * 
     * @param int $providerId Provider user ID
     * @param int $newUserId New user ID
     * @param string $userType Type of new user
     * @return void
     */
    private static function sendAffiliateNotification($providerId, $newUserId, $userType)
    {
        // Get new user details
        $user = DB::select('select first_name, last_name from users WHERE id=?', [$newUserId]);
        
        if (empty($user)) {
            return;
        }
        
        $userName = $user[0]->first_name . ' ' . $user[0]->last_name;
        
        $title = 'New Network Member!';
        $message = $userName . ' has joined your network as a ' . $userType . ' through your affiliate link!';
        
        // Insert notification
        DB::insert('
            INSERT INTO notifications (user_id, title, message, date, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ', [
            $providerId,
            $title,
            $message,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Check if user can search for providers
     * 
     * @param object $user User object
     * @return bool
     */
    public static function canSearchProviders($user)
    {
        return $user->network_locked == 0;
    }
    
    /**
     * Get network providers for a user
     * 
     * @param int $userId User ID
     * @return array Array of provider IDs
     */
    public static function getNetworkProviders($userId)
    {
        $providers = [];
        
        // Get from patients table
        $relationships = DB::select('
            SELECT doctor, pharmacy, hospital 
            FROM patients 
            WHERE user = ? AND (doctor_approve = 1 OR pharmacy_approve = 1 OR hospital_approve = 1)
        ', [$userId]);
        
        foreach ($relationships as $rel) {
            if ($rel->doctor) $providers[] = $rel->doctor;
            if ($rel->pharmacy) $providers[] = $rel->pharmacy;
            if ($rel->hospital) $providers[] = $rel->hospital;
        }
        
        return array_unique($providers);
    }
    
    /**
     * Calculate commission rate based on patient count
     * 
     * @param int $patientCount Number of active patients
     * @return float Commission percentage (5.0 to 15.0)
     */
    public static function calculateCommissionRate($patientCount)
    {
        if ($patientCount < 10) return 5.0;
        if ($patientCount < 40) return 7.5;
        if ($patientCount < 70) return 10.0;
        if ($patientCount < 100) return 12.5;
        return 15.0;
    }
    
    /**
     * Record commission for a transaction
     * 
     * @param int $userId User who made purchase
     * @param float $amount Transaction amount
     * @param string $transactionType Type of transaction
     * @param int $transactionId Reference to original transaction
     * @return bool Success status
     */
    public static function recordCommission($userId, $amount, $transactionType, $transactionId = null)
    {
        // Get user's affiliate provider
        $user = DB::select('
            SELECT affiliate_provider_id, registration_source 
            FROM users 
            WHERE id = ?
        ', [$userId]);
        
        if (empty($user) || !$user[0]->affiliate_provider_id) {
            return false; // No affiliate provider
        }
        
        $providerId = $user[0]->affiliate_provider_id;
        
        // Get provider's patient count for commission calculation
        $provider = DB::select('
            SELECT active_patients_count 
            FROM users 
            WHERE id = ?
        ', [$providerId]);
        
        if (empty($provider)) {
            return false;
        }
        
        $patientCount = $provider[0]->active_patients_count;
        $commissionRate = self::calculateCommissionRate($patientCount);
        $commissionAmount = $amount * ($commissionRate / 100);
        
        // Insert commission record
        try {
            DB::insert('
                INSERT INTO network_commissions 
                (provider_id, user_id, transaction_type, transaction_id, amount, commission_percentage, commission_amount, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, "pending", ?, ?)
            ', [
                $providerId,
                $userId,
                $transactionType,
                $transactionId,
                $amount,
                $commissionRate,
                $commissionAmount,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
