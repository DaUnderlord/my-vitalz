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
        Schema::create('marketplace_drugs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_rep_id');
            $table->string('drug_name');
            $table->string('generic_name')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->decimal('wholesale_price', 12, 2);
            $table->decimal('suggested_retail_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->string('unit')->default('tablets');
            $table->string('photo')->nullable();
            $table->string('state');
            $table->string('city')->nullable();
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->timestamps();
            
            $table->index('sales_rep_id');
            $table->index('state');
            $table->index('status');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_drugs');
    }
};
