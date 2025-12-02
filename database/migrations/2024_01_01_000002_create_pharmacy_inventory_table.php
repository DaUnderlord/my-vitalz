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
        Schema::create('pharmacy_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pharmacy_id');
            $table->string('medication_name');
            $table->string('generic_name')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('dosage')->nullable();
            $table->string('form')->nullable(); // tablet, capsule, syrup, injection
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('doctor_price', 10, 2)->default(0); // discounted price for doctors
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamps();
            
            $table->index(['pharmacy_id', 'medication_name']);
            $table->index(['pharmacy_id', 'stock_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_inventory');
    }
};
