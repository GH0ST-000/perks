<?php

namespace App\Http\Controllers;

use App\Models\PremiumOffer;
use App\Models\Category;
use App\Models\CompanyRequest;
use App\Services\MembershipService;
use App\Models\PartnerRequest;
use App\Models\BlogPost;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    private function userHasOfferAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return app(MembershipService::class)->canActivateOffers($user);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $hasMembership = $this->userHasOfferAccess();

        $premiumOffers = PremiumOffer::with(['partner', 'partner.categories'])
            ->publicVisible()
            ->where('is_premium', true)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        // Get all categories for the categories section
        $categories = Category::orderBy('name')->get();

        // Get active sliders ordered by order field
        $sliders = Slider::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        // Get active testimonials ordered by order field
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('welcome', compact('premiumOffers', 'categories', 'sliders', 'testimonials', 'hasMembership'));
    }

    public function allOffers(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $categories = Category::orderBy('name')->get();
        $hasMembership = $this->userHasOfferAccess();

        $query = PremiumOffer::with(['partner', 'partner.categories'])
            ->publicVisible();

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

        return view('offers.index', compact('offers', 'categories', 'hasMembership'));
    }

    public function showOffer(PremiumOffer $offer): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        if ($offer->status !== PremiumOffer::STATUS_APPROVED || $offer->day_left <= 0) {
            return redirect()->route('offers.index')->with('error', 'This offer is not available.');
        }

        // Load relationships
        $offer->load(['partner', 'partner.categories']);

        // Get related offers from the same partner (all non-expired offers)
        $relatedOffers = PremiumOffer::with(['partner', 'partner.categories'])
            ->publicVisible()
            ->where('partner_id', $offer->partner_id)
            ->where('id', '!=', $offer->id)
            ->limit(4)
            ->get();

        $userClaim = null;
        $membership = app(MembershipService::class);
        $hasMembership = auth()->check() && $membership->canActivateOffers(auth()->user());
        $displayPCoins = auth()->check()
            ? $membership->pCoinsForOffer($offer, auth()->user())
            : $offer->p_coins_reward;
        $membershipPlan = auth()->check() ? $membership->plan(auth()->user()) : null;

        if (auth()->check()) {
            $userClaim = \App\Models\OfferClaim::query()
                ->where('user_id', auth()->id())
                ->where('premium_offer_id', $offer->id)
                ->first();
        }

        return view('offers.show', compact(
            'offer',
            'relatedOffers',
            'userClaim',
            'hasMembership',
            'displayPCoins',
            'membershipPlan',
        ));
    }

    public function companies(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('companies');
    }

    public function partners(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('partners');
    }

    public function about(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('about');
    }

    public function storeCompanyRequest(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'employees' => 'nullable|integer|min:1',
        ]);

        CompanyRequest::create($validated);

        return redirect()->back()->with('success', 'მადლობა თქვენი ინტერესისთვის! ჩვენი გუნდი მალე დაგიკავშირდებათ.');
    }

    public function storePartnerRequest(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        PartnerRequest::create($validated);

        return redirect()->back()->with('success', 'მადლობა თქვენი ინტერესისთვის! ჩვენი პარტნიორობის მენეჯერი მალე დაგიკავშირდებათ.');
    }

    public function blog(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Start query with published posts only
        $query = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Apply category filter
        if ($request->filled('category') && $request->input('category') !== 'All') {
            $query->where('category', $request->input('category'));
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Get paginated posts
        $posts = $query->orderBy('published_at', 'desc')->paginate(12)->appends($request->query());

        // Get all categories for filter
        $categories = BlogPost::where('is_published', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('blog.index', compact('posts', 'categories'));
    }

    public function blogPost(string $slug): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        // Find post by slug
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Get related posts from same category
        $relatedPosts = BlogPost::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function claimOffer(PremiumOffer $offer): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        $membership = app(MembershipService::class);

        if ($offer->status !== PremiumOffer::STATUS_APPROVED || $offer->day_left <= 0) {
            return redirect()->back()->with('error', 'ეს შეთავაზება ხელმისაწვდომი არ არის.');
        }

        if (! $membership->canActivateOffers($user)) {
            return redirect()->route('subscriptions.index')->with(
                'error',
                'შეთავაზების მისაღებად გაააქტიურეთ Member ან Limited პაკეტი.'
            );
        }

        $existingClaim = \App\Models\OfferClaim::where('user_id', $user->id)
            ->where('premium_offer_id', $offer->id)
            ->first();

        if ($existingClaim) {
            return redirect()->back()->with('info', 'თქვენ უკვე მიიღეთ ეს შეთავაზება.');
        }

        $cardType = $membership->cardTypeFor($user) ?? 'standard';
        $discount = $membership->discountFor($offer, $user);

        $claim = app(\App\Services\OfferClaimService::class)->createClaim(
            $user,
            $offer,
            $cardType,
            $discount
        );

        return redirect()->back()->with(
            'success',
            'შეთავაზება წარმატებით მიღებულია! თქვენი კოდი: '.$claim->redemption_code
        );
    }
}

