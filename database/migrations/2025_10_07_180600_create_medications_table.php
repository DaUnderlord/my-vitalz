<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('medications')) {
            Schema::create('medications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user'); // patient id
                $table->unsignedBigInteger('prescription_id');
                $table->string('period_taken')->nullable(); // e.g., morning/afternoon/evening
                // legacy date string (functions::take_medication uses d-M-Y h:ia)
                $table->string('date')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['prescription_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
