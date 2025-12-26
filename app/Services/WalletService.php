<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Add P coins to user's wallet
     */
    public function credit(User $user, int $amount, string $type = 'credit', ?string $description = null, ?string $referenceType = null, ?int $referenceId = null, ?array $metadata = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $type, $description, $referenceType, $referenceId, $metadata) {
            // Update user balance
            $user->increment('p_coins', $amount);
            $user->refresh();

            // Create transaction record
            return WalletTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'balance_after' => $user->p_coins,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'metadata' => $metadata,
            ]);
        });
    }

    /**
     * Deduct P coins from user's wallet
     */
    public function debit(User $user, int $amount, string $type = 'debit', ?string $description = null, ?string $referenceType = null, ?int $referenceId = null, ?array $metadata = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $type, $description, $referenceType, $referenceId, $metadata) {
            // Check if user has enough balance
            if ($user->p_coins < $amount) {
                throw new \Exception('Insufficient balance');
            }

            // Update user balance
            $user->decrement('p_coins', $amount);
            $user->refresh();

            // Create transaction record (negative amount)
            return WalletTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => -$amount,
                'balance_after' => $user->p_coins,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'metadata' => $metadata,
            ]);
        });
    }

    /**
     * Get user's wallet statistics
     */
    public function getStatistics(User $user): array
    {
        $totalEarned = WalletTransaction::where('user_id', $user->id)
            ->where('amount', '>', 0)
            ->sum('amount');

        $totalSpent = abs(WalletTransaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->sum('amount'));

        return [
            'current_balance' => $user->p_coins ?? 0,
            'total_earned' => $totalEarned,
            'total_spent' => $totalSpent,
        ];
    }
}

