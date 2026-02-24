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
            $table->decimal('standard_discount', 5, 2)->default(0)->after('discount');
            $table->decimal('premium_discount', 5, 2)->default(0)->after('standard_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropColumn(['standard_discount', 'premium_discount']);
        });
    }
};
