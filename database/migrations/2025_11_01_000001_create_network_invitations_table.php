<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // Who sent the invitation
            $table->string('sender_type'); // doctor, hospital, pharmacy, patient
            $table->unsignedBigInteger('receiver_id')->nullable(); // If receiver exists in system
            $table->string('receiver_email')->nullable(); // If inviting by email
            $table->string('receiver_phone')->nullable(); // If inviting by phone
            $table->string('receiver_type'); // Expected role: doctor, hospital, pharmacy, patient
            $table->string('invitation_code', 50)->unique(); // Unique invitation code
            $table->text('message')->nullable(); // Personal message
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable(); // Invitation expiry (30 days default)
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('receiver_email');
            $table->index('receiver_phone');
            $table->index('invitation_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_invitations');
    }
}
