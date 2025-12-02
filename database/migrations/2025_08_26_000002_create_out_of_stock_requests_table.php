<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('out_of_stock_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pharmacy_id');
            $table->string('drug_name');
            $table->string('dosage')->nullable();
            $table->integer('quantity');
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->string('partner_type')->nullable(); // doctor|hospital|pharmacy
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending|fulfilled|declined
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('out_of_stock_requests');
    }
};
