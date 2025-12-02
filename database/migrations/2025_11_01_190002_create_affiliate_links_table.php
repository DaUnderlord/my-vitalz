<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Affiliate Links Table
 * 
 * Purpose: Track affiliate links for network building
 * Features:
 * - Unique link generation per provider
 * - Track clicks, registrations, and conversions
 * - Support multiple link types (patient, doctor, pharmacy, hospital)
 * - Link activation/deactivation
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
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            
            // Provider information
            $table->unsignedBigInteger('provider_id')
                ->comment('User ID of the provider who owns this link');
            $table->enum('provider_type', ['doctor', 'pharmacy', 'hospital'])
                ->comment('Type of provider');
            
            // Link details
            $table->string('link_code', 20)
                ->unique()
                ->comment('Unique tracking code embedded in URL');
            $table->text('link_url')
                ->comment('Full URL with tracking code');
            $table->enum('target_type', ['patient', 'doctor', 'pharmacy', 'hospital'])
                ->comment('Who this link is intended for');
            
            // Tracking metrics
            $table->integer('clicks')
                ->default(0)
                ->comment('Number of times link was clicked');
            $table->integer('registrations')
                ->default(0)
                ->comment('Number of successful registrations');
            $table->integer('active_users')
                ->default(0)
                ->comment('Number of currently active users from this link');
            
            // Link status
            $table->boolean('is_active')
                ->default(1)
                ->comment('1 = active, 0 = deactivated');
            
            // Custom message template (optional)
            $table->text('custom_message')
                ->nullable()
                ->comment('Custom invitation message for this link');
            
            // Timestamps
            $table->timestamp('last_used_at')
                ->nullable()
                ->comment('Last time someone clicked this link');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('provider_id');
            $table->index('link_code');
            $table->index('provider_type');
            $table->index('target_type');
            $table->index('is_active');
            $table->index('created_at');
            
            // Composite index for common queries
            $table->index(['provider_id', 'is_active']);
            $table->index(['provider_type', 'target_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};
