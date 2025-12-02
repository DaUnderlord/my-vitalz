<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_owner_id'); // Doctor/Hospital/Pharmacy who owns the network
            $table->string('network_owner_type'); // doctor, hospital, pharmacy
            $table->unsignedBigInteger('member_id'); // The member in the network
            $table->string('member_type'); // doctor, hospital, pharmacy, patient
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->text('block_reason')->nullable();
            $table->boolean('can_view_vitals')->default(true);
            $table->boolean('can_prescribe')->default(true); // For doctors
            $table->boolean('can_refer')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('network_owner_id');
            $table->index('member_id');
            $table->index(['network_owner_id', 'member_id']);
            $table->index('status');
            
            // Unique constraint: prevent duplicate memberships
            $table->unique(['network_owner_id', 'network_owner_type', 'member_id', 'member_type'], 'unique_network_membership');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_members');
    }
}
