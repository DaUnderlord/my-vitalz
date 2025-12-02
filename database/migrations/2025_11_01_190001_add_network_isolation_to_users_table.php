<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add Network Isolation Fields to Users Table
 * 
 * Purpose: Enable affiliate network tracking and network isolation
 * Features:
 * - Track how user registered (direct vs affiliate link)
 * - Lock users to their affiliate provider's network
 * - Track which provider brought them to platform
 * - Support for city and practice location
 * 
 * @author MyVitalz Development Team
 * @version 1.0.0
 * @date 2025-11-01
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Registration source tracking
            if (!Schema::hasColumn('users', 'registration_source')) {
                $table->string('registration_source', 50)
                    ->default('direct')
                    ->after('referral')
                    ->comment('How user registered: direct, doctor_link, pharmacy_link, hospital_link');
                $table->index('registration_source');
            }
            
            // Affiliate provider tracking
            if (!Schema::hasColumn('users', 'affiliate_provider_id')) {
                $table->unsignedBigInteger('affiliate_provider_id')
                    ->nullable()
                    ->after('registration_source')
                    ->comment('ID of provider who brought user to platform (if affiliate)');
                $table->index('affiliate_provider_id');
            }
            
            // Network lock flag
            if (!Schema::hasColumn('users', 'network_locked')) {
                $table->boolean('network_locked')
                    ->default(0)
                    ->after('affiliate_provider_id')
                    ->comment('1 = locked to affiliate network, 0 = can search freely');
                $table->index('network_locked');
            }
            
            // Location fields
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)
                    ->nullable()
                    ->after('state')
                    ->comment('City of residence or practice');
                $table->index('city');
            }
            
            if (!Schema::hasColumn('users', 'practice_location')) {
                $table->string('practice_location', 255)
                    ->nullable()
                    ->after('city')
                    ->comment('Primary practice location for doctors');
            }
            
            // Enhanced public profile flag
            if (!Schema::hasColumn('users', 'public_profile')) {
                $table->boolean('public_profile')
                    ->default(0)
                    ->after('public')
                    ->comment('1 = discoverable in search, 0 = invitation only');
                $table->index('public_profile');
            }
            
            // Network activity tracking
            if (!Schema::hasColumn('users', 'last_network_activity')) {
                $table->timestamp('last_network_activity')
                    ->nullable()
                    ->after('updated_at')
                    ->comment('Last time user performed network-related action');
            }
            
            // Patient count cache for commission calculation
            if (!Schema::hasColumn('users', 'active_patients_count')) {
                $table->integer('active_patients_count')
                    ->default(0)
                    ->after('last_network_activity')
                    ->comment('Cached count of active patients (for commission tier)');
                $table->index('active_patients_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop in reverse order
            $columns = [
                'active_patients_count',
                'last_network_activity',
                'public_profile',
                'practice_location',
                'city',
                'network_locked',
                'affiliate_provider_id',
                'registration_source'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    // Drop indexes first if they exist
                    if (in_array($column, ['registration_source', 'affiliate_provider_id', 'network_locked', 'city', 'public_profile', 'active_patients_count'])) {
                        try {
                            $table->dropIndex(['users_' . $column . '_index']);
                        } catch (\Exception $e) {
                            // Index might not exist, continue
                        }
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
