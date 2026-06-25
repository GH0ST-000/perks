<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bog_payments', function (Blueprint $table) {
            $table->foreignId('partner_marketing_subscription_id')
                ->nullable()
                ->after('subscription_id')
                ->constrained('partner_marketing_subscriptions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bog_payments', function (Blueprint $table) {
            $table->dropForeign(['partner_marketing_subscription_id']);
            $table->dropColumn('partner_marketing_subscription_id');
        });
    }
};
