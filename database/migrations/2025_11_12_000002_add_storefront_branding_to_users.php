<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Storefront branding fields for sales reps
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'storefront_logo')) {
                $table->string('storefront_logo')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_banner')) {
                $table->string('storefront_banner')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_primary_color')) {
                $table->string('storefront_primary_color')->default('#5a5fc7');
            }
            if (!Schema::hasColumn('users', 'storefront_secondary_color')) {
                $table->string('storefront_secondary_color')->default('#4a4eb3');
            }
            if (!Schema::hasColumn('users', 'storefront_description')) {
                $table->text('storefront_description')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_tagline')) {
                $table->string('storefront_tagline')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_phone')) {
                $table->string('storefront_phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_email')) {
                $table->string('storefront_email')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_website')) {
                $table->string('storefront_website')->nullable();
            }
            if (!Schema::hasColumn('users', 'storefront_active')) {
                $table->boolean('storefront_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'storefront_logo',
                'storefront_banner',
                'storefront_primary_color',
                'storefront_secondary_color',
                'storefront_description',
                'storefront_tagline',
                'storefront_phone',
                'storefront_email',
                'storefront_website',
                'storefront_active'
            ]);
        });
    }
};
