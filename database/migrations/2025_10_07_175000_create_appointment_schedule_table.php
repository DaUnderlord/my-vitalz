<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('appointment_schedule')) {
            Schema::create('appointment_schedule', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');
                // store HH:MM (24h) as strings; controllers treat as plain values
                $table->string('monday_start')->nullable();
                $table->string('monday_end')->nullable();
                $table->string('tuesday_start')->nullable();
                $table->string('tuesday_end')->nullable();
                $table->string('wednesday_start')->nullable();
                $table->string('wednesday_end')->nullable();
                $table->string('thursday_start')->nullable();
                $table->string('thursday_end')->nullable();
                $table->string('friday_start')->nullable();
                $table->string('friday_end')->nullable();
                $table->string('saturday_start')->nullable();
                $table->string('saturday_end')->nullable();
                $table->string('sunday_start')->nullable();
                $table->string('sunday_end')->nullable();
                $table->timestamps();

                $table->index(['user']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_schedule');
    }
};
