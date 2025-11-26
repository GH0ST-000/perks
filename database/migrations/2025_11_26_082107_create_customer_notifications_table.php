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
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('customer_phone');
            $table->string('verification_code');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->decimal('discount', 8, 2)->default(0);
            $table->integer('usage_count')->default(0);
            $table->enum('status', ['pending', 'verified', 'used'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'manager_id']);
            $table->index('customer_phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};
