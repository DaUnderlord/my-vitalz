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
        Schema::create('e_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_id')->unique();
            $table->unsignedBigInteger('pharmacy_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('hospital_id')->nullable();
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->integer('quantity');
            $table->text('instructions')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamp('prescribed_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['pharmacy_id', 'status']);
            $table->index(['doctor_id', 'patient_id']);
            $table->index('prescription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_prescriptions');
    }
};
