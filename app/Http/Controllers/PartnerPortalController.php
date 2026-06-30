<?php

namespace App\Http\Controllers;

use App\Models\PremiumOffer;
use App\Services\PartnerMarketingService;
use App\Services\PartnerMarketingSubscriptionService;
use App\Services\PartnerOfferService;
use App\Services\PartnerPortalService;
use App\Services\PartnerScannerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PartnerPortalController extends Controller
{
    public function __construct(
        private PartnerPortalService $portal,
        private PartnerOfferService $offers,
        private PartnerMarketingService $marketing,
        private PartnerMarketingSubscriptionService $marketingSubscriptions,
        private PartnerScannerService $scanner
    ) {}

    public function dashboard(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $stats = $this->portal->getDashboardStats($partner);

        return view('partner.dashboard', [
            'partner' => $partner,
            'user' => auth()->user(),
            'stats' => $stats,
        ]);
    }

    public function scanner(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        return view('partner.scanner', compact('partner'));
    }

    public function scannerSearch(Request $request): JsonResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'query' => ['required', 'string', 'max:64'],
        ]);

        try {
            $result = $this->scanner->search($partner, $validated['query']);

            return response()->json($result);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Partner scanner search failed', [
                'partner_id' => $partner->id,
                'query' => $validated['query'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'სამწუხაროდ ძებნა ვერ მოხერხდა. სცადეთ ხელახლა.',
            ], 500);
        }
    }

    public function scannerVerify(Request $request): JsonResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'token' => ['required', 'string'],
            'code' => ['required', 'string', 'size:4'],
        ]);

        try {
            $result = $this->scanner->verifyAndComplete(
                $partner,
                $validated['token'],
                $validated['code']
            );

            return response()->json($result);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function offers(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $offers = $this->portal->getOffers($partner)
            ->map(fn (PremiumOffer $offer) => $this->offers->toPortalArray($offer));

        return view('partner.offers', compact('partner', 'offers'));
    }

    public function createOffer(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        return view('partner.offer-form', [
            'partner' => $partner,
            'editing' => false,
            'offerId' => null,
            'formAction' => route('partner.offers.store'),
            'existingImageUrl' => null,
            'initialForm' => $this->offerFormDefaults(),
        ]);
    }

    public function editOffer(PremiumOffer $offer): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner || $offer->partner_id !== $partner->id || ! $offer->partnerCanEdit()) {
            abort(403);
        }

        $portalOffer = $this->offers->toPortalArray($offer);

        return view('partner.offer-form', [
            'partner' => $partner,
            'editing' => true,
            'offerId' => $offer->id,
            'formAction' => route('partner.offers.update', $offer),
            'existingImageUrl' => $portalOffer['image'],
            'initialForm' => $this->offerFormDefaults($portalOffer),
        ]);
    }

    public function storeOffer(Request $request): RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $validated = $this->validateOffer($request, route('partner.offers.create'));

        $this->offers->create($partner, $validated, $request->file('image'));

        return redirect()
            ->route('partner.offers')
            ->with('success', 'შეთავაზება გაგზავნილია ადმინისტრატორთან დასადასტურებლად.');
    }

    public function updateOffer(Request $request, PremiumOffer $offer): RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner || $offer->partner_id !== $partner->id || ! $offer->partnerCanEdit()) {
            abort(403);
        }

        $wasApproved = $offer->isApproved();

        $validated = $this->validateOffer($request, route('partner.offers.edit', $offer));
        $validated['remove_image'] = $request->boolean('remove_image');

        $this->offers->update($offer, $validated, $request->file('image'));

        $message = $wasApproved
            ? 'შეთავაზება განახლდა და ხელახლა გაიგზავნა დადასტურებისთვის.'
            : 'შეთავაზება განახლდა.';

        return redirect()->route('partner.offers')->with('success', $message);
    }

    public function destroyOffer(PremiumOffer $offer): RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner || $offer->partner_id !== $partner->id || ! $offer->partnerCanDelete()) {
            abort(403);
        }

        $this->offers->delete($offer);

        return redirect()->route('partner.offers')->with('success', 'შეთავაზება წაიშალა.');
    }

    /**
     * @param  array<string, mixed>|null  $offer
     * @return array<string, string>
     */
    private function offerFormDefaults(?array $offer = null): array
    {
        return [
            'title' => old('title', $offer['title'] ?? ''),
            'header_text' => old('header_text', $offer['header_text'] ?? ''),
            'description' => old('description', $offer['description'] ?? ''),
            'discount' => old('discount', isset($offer['discount']) ? (string) $offer['discount'] : '20'),
            'p_coins' => old('p_coins_reward', isset($offer['p_coins']) ? (string) $offer['p_coins'] : '0'),
            'period' => old('period', $offer['period'] ?? '1 თვე'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateOffer(Request $request, string $redirectRoute): array
    {
        $validator = Validator::make($request->all(), $this->offerRules());

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException(
                $validator,
                redirect($redirectRoute)->withInput()->withErrors($validator)
            );
        }

        return $validator->validated();
    }

    /**
     * @return array<string, mixed>
     */
    private function offerRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'header_text' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'discount' => ['required', 'integer', 'min:1', 'max:100'],
            'p_coins_reward' => ['required', 'integer', 'min:0'],
            'period' => ['required', 'in:1 თვე,2 თვე,3 თვე'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function marketing(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        return view('partner.marketing', [
            'partner' => $partner,
            'packages' => $this->marketing->packages(),
            'activeSubscription' => $partner->activeMarketingSubscription,
        ]);
    }

    public function orderMarketing(Request $request): RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'package' => ['required', 'in:social,platinum,executive'],
        ]);

        return $this->marketingSubscriptions->initiate(
            $partner,
            $request->user(),
            $validated['package']
        );
    }

    public function history(Request $request): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $periodFilters = $this->portal->historyPeriodFilters();
        $period = (string) $request->query('period', '28');
        if (! array_key_exists($period, $periodFilters)) {
            $period = '28';
        }
        $history = $this->portal->getVisitHistory($partner, $period);

        return view('partner.history', [
            'partner' => $partner,
            'visits' => $history['visits'],
            'period' => $history['period'],
            'periodFilters' => $periodFilters,
        ]);
    }

    public function settings(): View|RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        return view('partner.settings', compact('partner'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $partner = $this->portal->resolvePartner();
        if (! $partner) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $partner->update($validated);

        app(\App\Services\PartnerAccountService::class)->syncLoginUser($partner->fresh());

        return redirect()->route('partner.settings')->with('success', 'პარამეტრები განახლდა');
    }
}
