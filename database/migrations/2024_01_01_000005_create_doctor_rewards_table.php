<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('prescription_id');
            $table->decimal('prescription_value', 10, 2);
            $table->decimal('reward_percentage', 5, 2)->default(5.00); // 5% default
            $table->decimal('reward_amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->date('earned_date');
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['pharmacy_id', 'doctor_id']);
            $table->index(['status', 'earned_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_rewards');
    }
};
