<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');
                $table->string('name')->nullable();
                $table->string('sku')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->text('description')->nullable();
                $table->string('photo')->nullable();
                $table->string('category')->nullable();
                // when a product is a reference to a shared product
                $table->unsignedBigInteger('product_ref')->nullable();
                // legacy date string like d-M-Y
                $table->string('date')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['product_ref']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
