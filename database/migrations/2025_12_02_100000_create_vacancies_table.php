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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('employment_type')->nullable(); // full-time, part-time, contract, freelance
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('GEL'); // GEL, USD, EUR
            $table->string('experience_level')->nullable(); // intern, junior, mid, senior, lead
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->date('expires_at')->nullable();
            $table->string('application_email')->nullable();
            $table->string('application_url')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('is_featured');
            $table->index('expires_at');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};

