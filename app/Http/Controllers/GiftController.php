<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\GiftRedemption;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiftController extends Controller
{
    public function __construct(
        private WalletService $wallet,
    ) {}

    /**
     * Display a listing of available gifts.
     */
    public function index()
    {
        $user = auth()->user();

        $gifts = Gift::with('partner')
            ->where('is_active', true)
            ->whereNotNull('partner_id')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $userRedemptions = $user->giftRedemptions()
            ->whereIn('gift_id', $gifts->pluck('id'))
            ->whereIn('status', [GiftRedemption::STATUS_PENDING, GiftRedemption::STATUS_USED])
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

        if (! $gift->isAvailable()) {
            return back()->with('error', 'ეს საჩუქარი არ არის ხელმისაწვდომი');
        }

        if (! $gift->partner_id) {
            return back()->with('error', 'ეს საჩუქარი ჯერ არ არის დაკავშირებული პარტნიორთან');
        }

        if ($user->p_coins < $gift->p_coins_cost) {
            return back()->with('error', 'არასაკმარისი P ქულები');
        }

        $existingRedemption = $user->giftRedemptions()
            ->where('gift_id', $gift->id)
            ->where('status', GiftRedemption::STATUS_PENDING)
            ->first();

        if ($existingRedemption) {
            return back()->with('info', 'თქვენ უკვე გაქვთ აქტიური კოდი ამ საჩუქრისთვის: '.$existingRedemption->redemption_code);
        }

        try {
            DB::beginTransaction();

            $this->wallet->debit(
                $user,
                $gift->p_coins_cost,
                'purchase',
                'საჩუქრის გადაცვლა: '.$gift->name,
                Gift::class,
                $gift->id,
            );

            $redemption = GiftRedemption::create([
                'user_id' => $user->id,
                'gift_id' => $gift->id,
                'p_coins_spent' => $gift->p_coins_cost,
                'status' => GiftRedemption::STATUS_PENDING,
                'redeemed_at' => now(),
                'expires_at' => now()->addMonths(3),
            ]);

            $redemption->update([
                'redemption_code' => $redemption->redemptionCodeFor(),
            ]);

            $gift->decreaseStock();

            DB::commit();

            return back()->with(
                'success',
                'საჩუქარი წარმატებით გადაიცვალა! თქვენი კოდი პარტნიორთან: '.$redemption->redemption_code
            );
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
            ->with(['gift.partner'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('gifts.my-gifts', [
            'redemptions' => $redemptions,
        ]);
    }
}
