<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user')) {
                    $table->unsignedBigInteger('user')->nullable()->after('id');
                    $table->index('user');
                }
                if (!Schema::hasColumn('notifications', 'description')) {
                    $table->text('description')->nullable()->after('message');
                }
                if (!Schema::hasColumn('notifications', 'link')) {
                    $table->string('link')->nullable()->after('description');
                }
                // Make seen nullable to support legacy queries using IS NULL
                try {
                    $table->boolean('seen')->nullable()->change();
                } catch (\Throwable $e) {
                    // Some DBs can't change easily; ignore if fails
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (Schema::hasColumn('notifications', 'user')) {
                    $table->dropIndex(['user']);
                    $table->dropColumn('user');
                }
                if (Schema::hasColumn('notifications', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('notifications', 'link')) {
                    $table->dropColumn('link');
                }
            });
        }
    }
};
