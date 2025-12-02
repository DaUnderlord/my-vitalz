<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('title')->nullable();
                $table->text('message');
                $table->boolean('seen')->default(false);
                // legacy code orders by `date`, keep it for compatibility
                $table->string('date')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'seen']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
