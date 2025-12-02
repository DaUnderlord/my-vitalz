<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('support_replies')) {
            Schema::create('support_replies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user');
                $table->string('support_id'); // references support.ticket_id
                $table->text('comment');
                // legacy date string
                $table->string('date')->nullable();
                $table->timestamps();

                $table->index(['user']);
                $table->index(['support_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_replies');
    }
};
