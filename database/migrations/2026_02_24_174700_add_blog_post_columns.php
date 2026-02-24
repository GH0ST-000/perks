<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // Check and add missing columns
            if (!Schema::hasColumn('blog_posts', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('slug');
            }
            
            if (!Schema::hasColumn('blog_posts', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('content');
            }
            
            if (!Schema::hasColumn('blog_posts', 'tags')) {
                $table->json('tags')->nullable()->after('category');
            }
            
            if (!Schema::hasColumn('blog_posts', 'author')) {
                $table->string('author')->nullable()->after('tags');
            }
            
            if (!Schema::hasColumn('blog_posts', 'read_time')) {
                $table->integer('read_time')->nullable()->comment('Reading time in minutes')->after('author');
            }
            
            if (!Schema::hasColumn('blog_posts', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('published_at');
            }
            
            if (!Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $columns = ['excerpt', 'featured_image', 'tags', 'author', 'read_time', 'meta_title', 'meta_description'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('blog_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
