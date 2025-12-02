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
        Schema::create('pharmacy_consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('pharmacy_patient_id');
            $table->enum('consultation_type', ['virtual', 'physical']);
            $table->timestamp('scheduled_at');
            $table->string('meeting_link')->nullable();
            $table->string('meeting_location')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            
            $table->index(['pharmacy_id', 'scheduled_at']);
            $table->index(['pharmacy_patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_consultations');
    }
};
