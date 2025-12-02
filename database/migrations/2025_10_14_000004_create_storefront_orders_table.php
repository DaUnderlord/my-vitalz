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
        Schema::create('storefront_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('sales_rep_id');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('doctor_commission', 12, 2)->default(0);
            $table->decimal('sales_rep_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->text('delivery_address');
            $table->string('delivery_state');
            $table->string('delivery_city');
            $table->string('delivery_phone');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('sales_rep_id');
            $table->index('order_status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storefront_orders');
    }
};
