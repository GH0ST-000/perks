<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            if (! Schema::hasColumn('premium_offers', 'status')) {
                $table->string('status')->default('approved')->after('partner_id');
            }
            if (! Schema::hasColumn('premium_offers', 'period')) {
                $table->string('period')->nullable()->after('status');
            }
            if (! Schema::hasColumn('premium_offers', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('period');
            }
            if (! Schema::hasColumn('premium_offers', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('premium_offers', function (Blueprint $table) {
            $columns = ['period', 'rejection_reason'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('premium_offers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
