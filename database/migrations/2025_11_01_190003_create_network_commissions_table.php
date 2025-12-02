<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Network Commissions Table
 * 
 * Purpose: Track lifetime earnings from affiliate network
 * Features:
 * - Track all purchases made by network members
 * - Calculate tiered commissions (5%-15% based on patient count)
 * - Support multiple transaction types
 * - Payment status tracking
 * 
 * Business Rules:
 * - <10 patients = 5% commission
 * - 10-39 patients = 7.5% commission
 * - 40-69 patients = 10% commission
 * - 70-99 patients = 12.5% commission
 * - 100+ patients = 15% commission
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
        Schema::create('network_commissions', function (Blueprint $table) {
            $table->id();
            
            // Commission recipient
            $table->unsignedBigInteger('provider_id')
                ->comment('Provider who earns the commission');
            
            // Transaction details
            $table->unsignedBigInteger('user_id')
                ->comment('User who made the purchase');
            $table->enum('transaction_type', [
                'drug_purchase',
                'consultation',
                'device_sale',
                'subscription',
                'appointment'
            ])->comment('Type of transaction');
            $table->unsignedBigInteger('transaction_id')
                ->nullable()
                ->comment('Reference to original transaction (order_id, appointment_id, etc)');
            
            // Financial details
            $table->decimal('amount', 10, 2)
                ->comment('Original transaction amount');
            $table->decimal('commission_percentage', 5, 2)
                ->comment('Commission rate applied (5.00 to 15.00)');
            $table->decimal('commission_amount', 10, 2)
                ->comment('Calculated commission amount');
            
            // Payment tracking
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])
                ->default('pending')
                ->comment('Payment status');
            $table->timestamp('approved_at')
                ->nullable()
                ->comment('When commission was approved');
            $table->timestamp('paid_at')
                ->nullable()
                ->comment('When commission was paid out');
            
            // Payment method tracking
            $table->string('payment_method', 50)
                ->nullable()
                ->comment('How commission was paid (bank_transfer, wallet, etc)');
            $table->string('payment_reference', 100)
                ->nullable()
                ->comment('Payment transaction reference');
            
            // Metadata
            $table->json('metadata')
                ->nullable()
                ->comment('Additional transaction details');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index('provider_id');
            $table->index('user_id');
            $table->index('transaction_type');
            $table->index('status');
            $table->index('created_at');
            $table->index('paid_at');
            
            // Composite indexes for common queries
            $table->index(['provider_id', 'status']);
            $table->index(['provider_id', 'created_at']);
            $table->index(['user_id', 'transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('network_commissions');
    }
};
