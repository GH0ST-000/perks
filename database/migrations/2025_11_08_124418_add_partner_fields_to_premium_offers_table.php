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
            $table->foreignId('partner_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_premium')->default(false);
            $table->boolean('package_purchased')->default(false);
            $table->timestamp('purchased_at')->nullable();
            $table->foreignId('purchased_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['purchased_by']);
            $table->dropColumn(['partner_id', 'is_premium', 'package_purchased', 'purchased_at', 'purchased_by']);
        });
    }
};
