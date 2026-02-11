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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('p_coins_cost'); // Cost in P coins
            $table->integer('stock')->default(0); // Available quantity
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('type')->default('voucher'); // voucher, product, service
            $table->json('metadata')->nullable(); // Additional data like promo codes, terms, etc.
            $table->timestamps();
        });

        // Table to track gift redemptions
        Schema::create('gift_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gift_id')->constrained()->onDelete('cascade');
            $table->integer('p_coins_spent');
            $table->string('redemption_code')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, completed, used, expired
            $table->text('notes')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_redemptions');
        Schema::dropIfExists('gifts');
    }
};

