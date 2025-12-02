<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Communities table - health-focused support groups
        if (!Schema::hasTable('communities')) {
            Schema::create('communities', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->text('description')->nullable();
                $table->string('category', 50)->nullable(); // e.g., 'diabetes', 'hypertension', 'mental_health', 'fitness', 'nutrition'
                $table->string('cover_image')->nullable();
                $table->string('icon', 50)->nullable(); // boxicon class
                $table->string('primary_color', 7)->default('#696cff');
                $table->unsignedBigInteger('created_by')->nullable(); // doctor/admin who created it
                $table->boolean('is_public')->default(true); // public vs invite-only
                $table->boolean('is_active')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->text('rules')->nullable(); // community guidelines
                $table->timestamps();
                
                $table->index(['category']);
                $table->index(['is_public', 'is_active']);
                $table->index(['is_featured']);
            });
        }

        // Community members
        if (!Schema::hasTable('community_members')) {
            Schema::create('community_members', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('community_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('role', ['member', 'moderator', 'admin'])->default('member');
                $table->boolean('notifications_enabled')->default(true);
                $table->timestamp('joined_at')->useCurrent();
                $table->timestamps();
                
                $table->unique(['community_id', 'user_id']);
                $table->index(['user_id']);
                $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            });
        }

        // Community posts
        if (!Schema::hasTable('community_posts')) {
            Schema::create('community_posts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('community_id');
                $table->unsignedBigInteger('user_id');
                $table->string('title', 200)->nullable();
                $table->text('content');
                $table->enum('post_type', ['discussion', 'question', 'announcement', 'poll', 'resource'])->default('discussion');
                $table->string('image')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_approved')->default(true); // for moderation
                $table->unsignedInteger('likes_count')->default(0);
                $table->unsignedInteger('comments_count')->default(0);
                $table->unsignedInteger('views_count')->default(0);
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['community_id', 'created_at']);
                $table->index(['user_id']);
                $table->index(['is_pinned']);
                $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            });
        }

        // Community post comments
        if (!Schema::hasTable('community_comments')) {
            Schema::create('community_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('parent_id')->nullable(); // for nested replies
                $table->text('content');
                $table->unsignedInteger('likes_count')->default(0);
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['post_id', 'created_at']);
                $table->index(['user_id']);
                $table->index(['parent_id']);
                $table->foreign('post_id')->references('id')->on('community_posts')->onDelete('cascade');
            });
        }

        // Post/comment likes
        if (!Schema::hasTable('community_likes')) {
            Schema::create('community_likes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('likeable_type', 50); // 'post' or 'comment'
                $table->unsignedBigInteger('likeable_id');
                $table->timestamps();
                
                $table->unique(['user_id', 'likeable_type', 'likeable_id']);
                $table->index(['likeable_type', 'likeable_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('community_likes');
        Schema::dropIfExists('community_comments');
        Schema::dropIfExists('community_posts');
        Schema::dropIfExists('community_members');
        Schema::dropIfExists('communities');
    }
};
