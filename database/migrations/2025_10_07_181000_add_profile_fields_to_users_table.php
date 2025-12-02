<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'photo')) {
                    $table->string('photo')->nullable()->after('password');
                }
                if (!Schema::hasColumn('users', 'about')) {
                    $table->text('about')->nullable()->after('photo');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->string('address')->nullable()->after('about');
                }
                if (!Schema::hasColumn('users', 'state')) {
                    $table->string('state')->nullable()->after('address');
                }
                if (!Schema::hasColumn('users', 'country')) {
                    $table->string('country')->nullable()->after('state');
                }
                if (!Schema::hasColumn('users', 'public')) {
                    // profile visibility flag used in controllers (nullable)
                    $table->tinyInteger('public')->nullable()->after('country');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'public')) {
                    $table->dropColumn('public');
                }
                if (Schema::hasColumn('users', 'country')) {
                    $table->dropColumn('country');
                }
                if (Schema::hasColumn('users', 'state')) {
                    $table->dropColumn('state');
                }
                if (Schema::hasColumn('users', 'address')) {
                    $table->dropColumn('address');
                }
                if (Schema::hasColumn('users', 'about')) {
                    $table->dropColumn('about');
                }
                if (Schema::hasColumn('users', 'photo')) {
                    $table->dropColumn('photo');
                }
            });
        }
    }
};
