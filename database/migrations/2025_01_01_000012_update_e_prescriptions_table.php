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
        Schema::table('e_prescriptions', function (Blueprint $table) {
            // Remove single medication fields (will use prescription_medications table instead)
            $table->dropColumn(['medication_name', 'dosage', 'frequency', 'quantity']);
            
            // Add new fields
            $table->enum('consultation_type', ['online', 'physical'])->default('online')->after('hospital_id');
            $table->enum('fulfillment_method', ['pickup', 'delivery'])->default('pickup')->after('consultation_type');
            $table->decimal('base_total', 10, 2)->default(0)->after('fulfillment_method');
            $table->decimal('markup_amount', 10, 2)->default(0)->after('base_total');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('markup_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('e_prescriptions', function (Blueprint $table) {
            $table->string('medication_name')->after('hospital_id');
            $table->string('dosage')->after('medication_name');
            $table->string('frequency')->after('dosage');
            $table->integer('quantity')->after('frequency');
            
            $table->dropColumn(['consultation_type', 'fulfillment_method', 'base_total', 'markup_amount', 'delivery_fee']);
        });
    }
};
