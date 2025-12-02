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
        Schema::create('pharmacy_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id')->unique();
            $table->decimal('doctor_markup_percentage', 5, 2)->default(15.00);
            $table->decimal('default_delivery_fee', 10, 2)->default(1500.00);
            $table->string('storefront_logo_url')->nullable();
            $table->decimal('doctor_discount_percentage', 5, 2)->default(10.00);
            $table->decimal('wholesale_discount_percentage', 5, 2)->default(20.00);
            $table->string('virtual_pharmacy_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_settings');
    }
};
