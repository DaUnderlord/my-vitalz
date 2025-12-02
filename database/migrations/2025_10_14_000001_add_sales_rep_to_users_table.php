<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sales_rep')) {
                $table->boolean('sales_rep')->default(false)->after('hospital');
            }
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->after('sales_rep');
            }
            if (!Schema::hasColumn('users', 'company_license')) {
                $table->string('company_license')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('company_license');
                $table->index('state');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('state');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'state')) {
                $table->dropIndex(['state']);
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('users', 'company_license')) {
                $table->dropColumn('company_license');
            }
            if (Schema::hasColumn('users', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('users', 'sales_rep')) {
                $table->dropColumn('sales_rep');
            }
        });
    }
};
