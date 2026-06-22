<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {
            $table->string('provider_otp_hash')->nullable()->after('otp_code');
            $table->string('otp_code', 6)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_otps', function (Blueprint $table) {
            $table->dropColumn('provider_otp_hash');
            $table->string('otp_code', 6)->nullable(false)->change();
        });
    }
};
