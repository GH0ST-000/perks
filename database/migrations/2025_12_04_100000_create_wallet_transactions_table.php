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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'credit', 'debit', 'reward', 'purchase', 'refund'
            $table->integer('amount'); // P coins amount (positive for credit, negative for debit)
            $table->integer('balance_after'); // Balance after this transaction
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable(); // e.g., 'PremiumOffer', 'Visit', etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related model
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

