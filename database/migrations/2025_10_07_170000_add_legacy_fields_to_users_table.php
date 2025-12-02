<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'authen')) {
                $table->string('authen')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'ref_code')) {
                $table->string('ref_code')->nullable()->after('authen');
                $table->index('ref_code');
            }
            if (!Schema::hasColumn('users', 'pharmacy')) {
                $table->boolean('pharmacy')->default(false)->after('ref_code');
            }
            if (!Schema::hasColumn('users', 'doctor')) {
                $table->boolean('doctor')->default(false)->after('pharmacy');
            }
            if (!Schema::hasColumn('users', 'date')) {
                // legacy stores formatted string like 07-Oct-2025
                $table->string('date')->nullable()->after('doctor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'date')) {
                $table->dropColumn('date');
            }
            if (Schema::hasColumn('users', 'doctor')) {
                $table->dropColumn('doctor');
            }
            if (Schema::hasColumn('users', 'pharmacy')) {
                $table->dropColumn('pharmacy');
            }
            if (Schema::hasColumn('users', 'ref_code')) {
                $table->dropIndex(['ref_code']);
                $table->dropColumn('ref_code');
            }
            if (Schema::hasColumn('users', 'authen')) {
                $table->dropColumn('authen');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('users', 'first_name')) {
                $table->dropColumn('first_name');
            }
        });
    }
};
