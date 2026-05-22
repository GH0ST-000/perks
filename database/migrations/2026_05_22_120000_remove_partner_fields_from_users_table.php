<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['account_type', 'partner_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('corporate')->after('role');
            $table->foreignId('partner_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
        });
    }
};
