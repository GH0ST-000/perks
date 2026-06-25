<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->nullOnDelete();
        });
    }
};
