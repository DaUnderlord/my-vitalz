<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('vital_readings')) {
            Schema::create('vital_readings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');
                $table->unsignedInteger('vitalz');
                $table->string('reading');
                $table->string('si_unit')->nullable();
                // legacy time() integer
                $table->unsignedBigInteger('date')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['vitalz']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_readings');
    }
};
