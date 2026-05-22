<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offer_claims', function (Blueprint $table) {
            $table->string('redemption_code', 32)->nullable()->unique()->after('status');
            $table->string('verification_code', 10)->nullable()->after('redemption_code');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_code');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('offer_claim_id')->nullable()->after('partner_id')->constrained('offer_claims')->nullOnDelete();
        });

        \App\Models\OfferClaim::query()
            ->whereNull('redemption_code')
            ->each(function (\App\Models\OfferClaim $claim): void {
                $claim->update(['redemption_code' => 'P-'.$claim->id]);
            });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['offer_claim_id']);
            $table->dropColumn('offer_claim_id');
        });

        Schema::table('offer_claims', function (Blueprint $table) {
            $table->dropColumn(['redemption_code', 'verification_code', 'verification_expires_at']);
        });
    }
};
