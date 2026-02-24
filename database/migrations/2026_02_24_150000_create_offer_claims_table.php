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
        Schema::create('offer_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('premium_offer_id')->constrained()->onDelete('cascade');
            $table->enum('card_type', ['standard', 'premium'])->default('standard');
            $table->decimal('discount_received', 5, 2);
            $table->enum('status', ['pending', 'used', 'expired'])->default('pending');
            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            
            // Prevent duplicate claims
            $table->unique(['user_id', 'premium_offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_claims');
    }
};
