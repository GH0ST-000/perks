<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gifts', function (Blueprint $table) {
            $table->foreignId('partner_id')
                ->nullable()
                ->after('id')
                ->constrained('partners')
                ->nullOnDelete();
        });

        Schema::table('gift_redemptions', function (Blueprint $table) {
            $table->string('verification_code', 10)->nullable()->after('redemption_code');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_code');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('gift_redemption_id')
                ->nullable()
                ->after('offer_claim_id')
                ->constrained('gift_redemptions')
                ->nullOnDelete();
        });

        DB::table('gift_redemptions')
            ->where('status', 'completed')
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['gift_redemption_id']);
            $table->dropColumn('gift_redemption_id');
        });

        Schema::table('gift_redemptions', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_expires_at']);
        });

        Schema::table('gifts', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn('partner_id');
        });
    }
};
