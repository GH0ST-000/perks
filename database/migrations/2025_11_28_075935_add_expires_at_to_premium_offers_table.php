<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->date('expires_at')->nullable()->after('day_left');
        });

        // Migrate existing data: calculate expires_at from day_left
        DB::statement('UPDATE premium_offers SET expires_at = DATE_ADD(NOW(), INTERVAL day_left DAY) WHERE day_left > 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
