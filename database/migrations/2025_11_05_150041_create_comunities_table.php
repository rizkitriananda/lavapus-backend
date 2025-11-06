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
       // 7. Branch Libraries
        Schema::create('branch_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('address');
            $table->text('maps_link')->nullable();
            $table->timestamps();
        });

        // 8. Post Communities
        Schema::create('post_communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });

        // 9. Video Post Communities
        Schema::create('video_post_communities', function (Blueprint $table) {
            $table->id();
            $table->text('video');
            $table->foreignId('post_community_id')->constrained('post_communities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 10. Image Post Communities
        Schema::create('image_post_communities', function (Blueprint $table) {
            $table->id();
            $table->text('image');
            $table->foreignId('post_community_id')->constrained('post_communities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 11. Like Communities
        Schema::create('like_communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_community_id')->constrained('post_communities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 12. Comment Communities
        Schema::create('comment_communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_community_id')->constrained('post_communities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_communities');
        Schema::dropIfExists('like_communities');
        Schema::dropIfExists('image_post_communities');
        Schema::dropIfExists('video_post_communities');
        Schema::dropIfExists('post_communities');
        Schema::dropIfExists('branch_libraries');
    }
};
