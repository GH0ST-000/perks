<?php

namespace App\Http\Controllers;

use App\Models\PremiumOffer;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Get premium offers with partner relationship (only non-expired)
        $premiumOffers = PremiumOffer::with(['partner', 'partner.categories'])
            ->whereDate('expires_at', '>=', now())
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        // Get all categories for the categories section
        $categories = Category::orderBy('name')->get();

        return view('welcome', compact('premiumOffers', 'categories'));
    }

    public function allOffers(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Get all categories for filtering
        $categories = Category::orderBy('name')->get();

        // Start query (only non-expired offers)
        $query = PremiumOffer::with(['partner', 'partner.categories'])
            ->whereDate('expires_at', '>=', now());

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('partner', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply location filter
        if ($request->filled('location') && $request->input('location') !== 'All') {
            $query->whereHas('partner', function($q) use ($request) {
                $q->where('city', $request->input('location'));
            });
        }

        // Apply category filter
        if ($request->filled('category') && $request->input('category') !== 'All') {
            $query->whereHas('partner.categories', function($q) use ($request) {
                $q->where('categories.id', $request->input('category'));
            });
        }

        // Get filtered offers with pagination
        $offers = $query->orderBy('id', 'desc')->paginate(12)->appends($request->query());

        return view('offers.index', compact('offers', 'categories'));
    }

    public function showOffer(PremiumOffer $offer): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Check if offer is expired, redirect to offers page if expired
        if ($offer->day_left <= 0) {
            return redirect()->route('offers.index')->with('error', 'This offer has expired.');
        }

        // Load relationships
        $offer->load(['partner', 'partner.categories']);

        // Get related offers from the same partner (only non-expired)
        $relatedOffers = PremiumOffer::with(['partner', 'partner.categories'])
            ->where('partner_id', $offer->partner_id)
            ->where('id', '!=', $offer->id)
            ->whereDate('expires_at', '>=', now())
            ->limit(4)
            ->get();

        return view('offers.show', compact('offer', 'relatedOffers'));
    }
}
