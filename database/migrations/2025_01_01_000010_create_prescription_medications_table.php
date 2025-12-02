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
        Schema::create('prescription_medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->string('drug_name');
            $table->string('type'); // tablet, capsule, syrup, injection
            $table->string('dosage');
            $table->integer('quantity');
            $table->integer('frequency_per_day');
            $table->string('frequency_time'); // 24-hourly, 12-hourly, etc
            $table->integer('duration_value');
            $table->string('duration_unit'); // days, weeks, months
            $table->text('instructions')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index('prescription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_medications');
    }
};
