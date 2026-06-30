<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('relationship');
            $table->timestamp('approved_at')->nullable()->after('is_active');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });

        DB::table('family_members')->where('is_active', true)->update(['status' => 'approved']);
        DB::table('family_members')->where('is_active', false)->update(['status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at', 'rejected_at']);
        });
    }
};
