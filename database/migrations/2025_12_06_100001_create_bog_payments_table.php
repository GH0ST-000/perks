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
        Schema::create('bog_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('external_order_id')->unique();
            $table->string('bog_order_id')->nullable()->unique();
            $table->string('type'); // 'one_time', 'subscription_initial', 'subscription_recurring'
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('GEL');
            $table->string('status')->default('pending'); // pending, processing, completed, failed, refunded
            $table->string('payment_method')->nullable(); // card, bank, etc.
            $table->text('description')->nullable();
            $table->string('card_id')->nullable(); // BOG card ID for saved cards
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->json('bog_response')->nullable();
            $table->json('callback_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['external_order_id']);
            $table->index(['bog_order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bog_payments');
    }
};

