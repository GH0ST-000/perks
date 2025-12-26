<?php

namespace App\Http\Controllers;

use App\Models\PCoinPackage;
use App\Models\PaymentMethod;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Display the user's wallet page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get wallet transactions
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        // Get P-Coin packages
        $packages = PCoinPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('p_coins')
            ->get();

        // Get payment methods
        $paymentMethods = PaymentMethod::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentBalance = $user->p_coins ?? 0;

        return view('wallet.index', [
            'user' => $user,
            'transactions' => $transactions,
            'packages' => $packages,
            'paymentMethods' => $paymentMethods,
            'currentBalance' => $currentBalance,
        ]);
    }

    /**
     * Store a new payment method.
     */
    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string'],
            'expiry_date' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
            'cvv' => ['required', 'string', 'regex:/^\d{3,4}$/'],
        ], [
            'cardholder_name.required' => 'მფლობელის სახელი სავალდებულოა.',
            'card_number.required' => 'ბარათის ნომერი სავალდებულოა.',
            'expiry_date.required' => 'ვადა სავალდებულოა.',
            'expiry_date.regex' => 'ვადა უნდა იყოს MM/YY ფორმატში.',
            'cvv.required' => 'CVV სავალდებულოა.',
            'cvv.regex' => 'CVV არასწორია.',
        ]);

        // Validate card number after removing spaces
        $cardNumber = preg_replace('/\s+/', '', $request->card_number);
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19 || !ctype_digit($cardNumber)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'ბარათის ნომერი არასწორია.',
                    'errors' => ['card_number' => ['ბარათის ნომერი არასწორია.']]
                ], 422);
            }
            return back()->withErrors(['card_number' => 'ბარათის ნომერი არასწორია.'])->withInput();
        }

        $user = $request->user();

        // Extract card details (cardNumber already cleaned above)
        $lastFour = substr($cardNumber, -4);
        
        // Detect card brand
        $brand = 'VISA';
        if (preg_match('/^5[1-5]/', $cardNumber)) {
            $brand = 'MASTERCARD';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            $brand = 'AMEX';
        }

        // Parse expiry date
        [$expiryMonth, $expiryYear] = explode('/', $request->expiry_date);
        $expiryYear = '20' . $expiryYear; // Convert YY to YYYY

        DB::transaction(function () use ($user, $brand, $lastFour, $expiryMonth, $expiryYear, $request) {
            // If this is the first card, make it default
            $isFirstCard = PaymentMethod::where('user_id', $user->id)->count() === 0;

            PaymentMethod::create([
                'user_id' => $user->id,
                'type' => 'card',
                'brand' => $brand,
                'last_four' => $lastFour,
                'expiry_month' => $expiryMonth,
                'expiry_year' => $expiryYear,
                'is_default' => $isFirstCard,
                'metadata' => [
                    'cardholder_name' => $request->cardholder_name,
                ],
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'ბარათი წარმატებით დაემატა.',
            ]);
        }

        return redirect()->route('wallet.index')->with('success', 'ბარათი წარმატებით დაემატა.');
    }
}

