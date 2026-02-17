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
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('bog_card_id')->nullable()->after('metadata');
            $table->string('cardholder_name')->nullable()->after('brand');
            $table->boolean('is_verified')->default(false)->after('is_default');
            
            $table->index('bog_card_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['bog_card_id', 'cardholder_name', 'is_verified']);
        });
    }
};

