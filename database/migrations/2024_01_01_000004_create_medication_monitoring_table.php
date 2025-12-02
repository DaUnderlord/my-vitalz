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
        Schema::create('medication_monitoring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('prescription_id');
            $table->string('medication_name');
            $table->integer('total_quantity');
            $table->integer('remaining_quantity');
            $table->date('last_refill_date');
            $table->date('next_refill_due');
            $table->enum('compliance_status', ['good', 'moderate', 'poor'])->default('good');
            $table->boolean('refill_reminder_sent')->default(false);
            $table->timestamp('last_compliance_check')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['pharmacy_id', 'patient_id']);
            $table->index(['next_refill_due', 'refill_reminder_sent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_monitoring');
    }
};
