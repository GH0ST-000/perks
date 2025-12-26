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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'card', 'bank_account', etc.
            $table->string('brand')->nullable(); // 'visa', 'mastercard', etc.
            $table->string('last_four'); // Last 4 digits of card
            $table->string('expiry_month')->nullable();
            $table->string('expiry_year')->nullable();
            $table->boolean('is_default')->default(false);
            $table->json('metadata')->nullable(); // Additional payment method data
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

