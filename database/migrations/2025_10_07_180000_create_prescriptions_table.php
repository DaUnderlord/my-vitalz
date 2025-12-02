<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('prescriptions')) {
            Schema::create('prescriptions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');   // patient id
                $table->unsignedBigInteger('doctor'); // doctor id
                $table->string('drug_name');
                $table->string('drug_type')->nullable();
                $table->string('duration')->nullable();
                $table->string('dosage')->nullable();
                $table->string('frequency')->nullable();
                $table->text('additional_info')->nullable();
                // legacy time() integer
                $table->unsignedBigInteger('date')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['doctor']);
                $table->index(['date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
