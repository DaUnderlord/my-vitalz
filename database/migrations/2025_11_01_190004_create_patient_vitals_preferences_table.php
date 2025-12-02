<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Patient Vitals Preferences Table
 * 
 * Purpose: Track which vitals each patient wants to monitor
 * Features:
 * - Patient selects vitals during registration
 * - Configure which vitals to forward to doctor
 * - Set custom alert thresholds per vital
 * - Enable/disable vitals anytime
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
        Schema::create('patient_vitals_preferences', function (Blueprint $table) {
            $table->id();
            
            // Patient and vital identification
            $table->unsignedBigInteger('patient_id')
                ->comment('User ID of the patient');
            $table->unsignedBigInteger('vital_id')
                ->comment('ID from allvitalz table');
            
            // Preference settings
            $table->boolean('is_enabled')
                ->default(1)
                ->comment('1 = tracking this vital, 0 = not tracking');
            $table->boolean('forward_to_doctor')
                ->default(1)
                ->comment('1 = auto-send to doctor, 0 = keep private');
            $table->boolean('show_on_dashboard')
                ->default(1)
                ->comment('1 = show on dashboard, 0 = hide (greyed out)');
            
            // Alert thresholds
            $table->decimal('alert_threshold_min', 10, 2)
                ->nullable()
                ->comment('Minimum normal value (alert if below)');
            $table->decimal('alert_threshold_max', 10, 2)
                ->nullable()
                ->comment('Maximum normal value (alert if above)');
            
            // Alert settings
            $table->boolean('enable_alerts')
                ->default(1)
                ->comment('1 = send alerts for abnormal readings');
            $table->enum('alert_frequency', ['immediate', 'daily', 'weekly'])
                ->default('immediate')
                ->comment('How often to send alerts');
            
            // Measurement frequency
            $table->enum('measurement_frequency', ['daily', 'weekly', 'monthly', 'as_needed'])
                ->default('as_needed')
                ->comment('How often patient should measure');
            $table->time('preferred_measurement_time')
                ->nullable()
                ->comment('Preferred time of day for measurements');
            
            // Timestamps
            $table->timestamp('last_measured_at')
                ->nullable()
                ->comment('Last time this vital was measured');
            $table->timestamps();
            
            // Unique constraint: one preference per patient per vital
            $table->unique(['patient_id', 'vital_id']);
            
            // Indexes
            $table->index('patient_id');
            $table->index('vital_id');
            $table->index('is_enabled');
            $table->index('forward_to_doctor');
            $table->index(['patient_id', 'is_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_vitals_preferences');
    }
};
