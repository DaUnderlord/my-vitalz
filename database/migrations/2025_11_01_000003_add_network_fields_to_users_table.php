<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworkFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Public/Private Profile for Doctors
            $table->boolean('public_profile')->default(0)->after('public'); // 0=private, 1=public
            
            // Network Preferences
            $table->boolean('allow_network_invitations')->default(1); // Can receive invitations
            $table->boolean('auto_accept_invitations')->default(0); // Auto-accept from verified sources
            
            // Guardian Management (for patients)
            $table->unsignedBigInteger('guardian_id')->nullable()->after('public_profile'); // Parent/Guardian user ID
            $table->string('guardian_relationship')->nullable(); // parent, spouse, caregiver, etc.
            $table->boolean('is_minor')->default(0); // Under 18
            
            // Vitals Preferences
            $table->json('vitals_preferences')->nullable(); // Which vitals to track
            $table->json('vitals_alert_thresholds')->nullable(); // Custom alert thresholds
            
            // Network Stats
            $table->integer('network_size')->default(0); // Cache of network member count
            $table->timestamp('last_network_activity')->nullable();
            
            // Indexes
            $table->index('public_profile');
            $table->index('guardian_id');
            $table->index('is_minor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'public_profile',
                'allow_network_invitations',
                'auto_accept_invitations',
                'guardian_id',
                'guardian_relationship',
                'is_minor',
                'vitals_preferences',
                'vitals_alert_thresholds',
                'network_size',
                'last_network_activity'
            ]);
        });
    }
}
