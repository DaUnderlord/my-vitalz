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
        Schema::create('storefront_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('doctor_inventory_id');
            $table->string('drug_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('wholesale_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storefront_order_items');
    }
};
