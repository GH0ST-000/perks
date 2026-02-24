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
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->integer('p_coins_reward')->default(0)->after('premium_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropColumn('p_coins_reward');
        });
    }
};
