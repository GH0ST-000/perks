<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display user's transaction and visit history.
     */
    public function index()
    {
        $user = auth()->user();

        // Get wallet transactions
        $transactions = $user->walletTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get visits
        $visits = $user->visits()
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Merge and sort by date
        $allHistory = collect()
            ->merge($transactions->map(function ($transaction) {
                return [
                    'type' => 'transaction',
                    'data' => $transaction,
                    'date' => $transaction->created_at,
                ];
            }))
            ->merge($visits->map(function ($visit) {
                return [
                    'type' => 'visit',
                    'data' => $visit,
                    'date' => $visit->created_at,
                ];
            }))
            ->sortByDesc('date')
            ->take(50); // Show latest 50 items

        return view('history.index', [
            'history' => $allHistory,
            'user' => $user,
        ]);
    }
}

