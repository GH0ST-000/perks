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
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('tag_text')->nullable()->after('title');
            $table->string('headline_before')->nullable()->after('tag_text');
            $table->string('headline_highlight')->nullable()->after('headline_before');
            $table->string('headline_after')->nullable()->after('headline_highlight');
            $table->text('sub_headline')->nullable()->after('headline_after');
            $table->string('button1_text')->nullable()->after('sub_headline');
            $table->string('button1_link')->nullable()->after('button1_text');
            $table->string('button2_text')->nullable()->after('button1_link');
            $table->string('button2_link')->nullable()->after('button2_text');
            $table->string('background_image')->nullable()->after('button2_link');
            $table->integer('order')->default(0)->after('background_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn([
                'tag_text',
                'headline_before',
                'headline_highlight',
                'headline_after',
                'sub_headline',
                'button1_text',
                'button1_link',
                'button2_text',
                'button2_link',
                'background_image',
                'order',
            ]);
        });
    }
};

