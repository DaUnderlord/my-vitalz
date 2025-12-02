<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('support')) {
            Schema::create('support', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');
                $table->string('ticket_id')->unique();
                $table->string('subject');
                $table->text('description')->nullable();
                $table->string('priority')->nullable();
                $table->string('status')->default('open');
                // legacy date strings
                $table->string('date')->nullable();
                $table->string('last_updated')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['ticket_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support');
    }
};
