<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftRedemption;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GiftController extends Controller
{
    /**
     * Display a listing of available gifts.
     */
    public function index()
    {
        $user = auth()->user();
        
        $gifts = Gift::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's redemptions for each gift
        $userRedemptions = $user->giftRedemptions()
            ->whereIn('gift_id', $gifts->pluck('id'))
            ->whereIn('status', ['completed', 'pending'])
            ->get()
            ->keyBy('gift_id');

        return view('gifts.index', [
            'gifts' => $gifts,
            'user' => $user,
            'userRedemptions' => $userRedemptions,
        ]);
    }

    /**
     * Redeem a gift.
     */
    public function redeem(Request $request, Gift $gift)
    {
        $user = auth()->user();

        // Check if gift is available
        if (!$gift->isAvailable()) {
            return back()->with('error', 'ეს საჩუქარი არ არის ხელმისაწვდომი');
        }

        // Check if user has enough P coins
        if ($user->p_coins < $gift->p_coins_cost) {
            return back()->with('error', 'არასაკმარისი P ქულები');
        }

        try {
            DB::beginTransaction();

            // Deduct P coins from user
            $user->decrement('p_coins', $gift->p_coins_cost);

            // Create wallet transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => -$gift->p_coins_cost,
                'balance_after' => $user->fresh()->p_coins,
                'description' => 'საჩუქრის გადაცვლა: ' . $gift->name,
                'reference_type' => Gift::class,
                'reference_id' => $gift->id,
            ]);

            // Create redemption record
            $redemption = GiftRedemption::create([
                'user_id' => $user->id,
                'gift_id' => $gift->id,
                'p_coins_spent' => $gift->p_coins_cost,
                'redemption_code' => 'GIFT-' . strtoupper(Str::random(10)),
                'status' => 'completed',
                'redeemed_at' => now(),
                'expires_at' => now()->addMonths(3), // Expires in 3 months
            ]);

            // Decrease gift stock
            $gift->decreaseStock();

            DB::commit();

            return back()->with('success', 'საჩუქარი წარმატებით გადაიცვალა! თქვენი კოდი: ' . $redemption->redemption_code);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'დაფიქსირდა შეცდომა. გთხოვთ სცადოთ თავიდან.');
        }
    }

    /**
     * Display user's redeemed gifts.
     */
    public function myGifts()
    {
        $redemptions = auth()->user()->giftRedemptions()
            ->with('gift')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gifts.my-gifts', [
            'redemptions' => $redemptions,
        ]);
    }
}

