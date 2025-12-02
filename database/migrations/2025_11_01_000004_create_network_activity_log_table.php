<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_activity_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Who performed the action
            $table->string('action_type'); // invite_sent, invite_accepted, member_added, member_removed, etc.
            $table->unsignedBigInteger('target_user_id')->nullable(); // Who was affected
            $table->string('target_user_type')->nullable(); // doctor, patient, etc.
            $table->text('description'); // Human-readable description
            $table->json('metadata')->nullable(); // Additional data
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('target_user_id');
            $table->index('action_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_activity_log');
    }
}
