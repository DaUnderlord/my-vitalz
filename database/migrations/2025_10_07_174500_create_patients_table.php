<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('patients')) {
            Schema::create('patients', function (Blueprint $table) {
                $table->bigIncrements('id');
                // Relationship endpoints (any of these can be involved in a link)
                $table->unsignedBigInteger('user')->nullable();      // primary patient user id
                $table->unsignedBigInteger('patient')->nullable();   // alt patient id reference used in some queries
                $table->unsignedBigInteger('doctor')->nullable();
                $table->unsignedBigInteger('hospital')->nullable();
                $table->unsignedBigInteger('pharmacy')->nullable();

                // Approval flags (nullable => pending)
                $table->tinyInteger('user_approve')->nullable();      // 1=approved, 2=declined
                $table->tinyInteger('doctor_approve')->nullable();    // 1=approved, 2=declined
                $table->tinyInteger('hospital_approve')->nullable();  // 1=approved, 2=declined
                $table->tinyInteger('pharmacy_approve')->nullable();  // 1=approved, 2=declined

                // Timestamps
                $table->timestamps();

                // Useful indexes for the common queries
                $table->index(['user']);
                $table->index(['patient']);
                $table->index(['doctor']);
                $table->index(['hospital']);
                $table->index(['pharmacy']);
                $table->index(['doctor_approve', 'user_approve']);
                $table->index(['hospital_approve', 'user_approve']);
                $table->index(['pharmacy_approve', 'user_approve']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
