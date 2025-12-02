<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'hospital')) {
                $table->boolean('hospital')->default(false)->after('pharmacy');
            }
            if (!Schema::hasColumn('users', 'license_type')) {
                $table->string('license_type')->nullable()->after('ref_code');
            }
            if (!Schema::hasColumn('users', 'specialization')) {
                $table->string('specialization')->nullable()->after('license_type');
            }
            if (!Schema::hasColumn('users', 'referral')) {
                $table->string('referral')->nullable()->after('ref_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'referral')) {
                $table->dropColumn('referral');
            }
            if (Schema::hasColumn('users', 'specialization')) {
                $table->dropColumn('specialization');
            }
            if (Schema::hasColumn('users', 'license_type')) {
                $table->dropColumn('license_type');
            }
            if (Schema::hasColumn('users', 'hospital')) {
                $table->dropColumn('hospital');
            }
        });
    }
};
