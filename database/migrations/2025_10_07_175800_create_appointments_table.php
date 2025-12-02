<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');   // patient id
                $table->unsignedBigInteger('doctor'); // doctor id
                $table->string('day')->nullable();    // e.g. Mon, Tue
                $table->string('date')->nullable();   // stored as string in code
                $table->unsignedBigInteger('start_time')->nullable(); // epoch int
                $table->unsignedBigInteger('end_time')->nullable();   // epoch int
                $table->string('channel')->nullable(); // e.g. video, in-person
                $table->string('status')->default('pending');
                $table->string('booking_date')->nullable(); // e.g. d-M-Y
                $table->tinyInteger('doc_accept')->nullable(); // 1=pending/accepted per code usage, 2/3 other states
                $table->string('address')->nullable();
                $table->decimal('cost', 12, 2)->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['doctor']);
                $table->index(['end_time']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
