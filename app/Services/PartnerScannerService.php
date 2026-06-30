<?php

namespace App\Services;

use App\Models\GiftRedemption;
use App\Models\OfferClaim;
use App\Models\Partner;
use App\Models\PremiumOffer;
use App\Models\User;
use App\Models\Visit;
use App\Services\MembershipService;
use App\Services\SmsService;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PartnerScannerService
{
    private const TYPE_OFFER_CLAIM = 'offer_claim';

    private const TYPE_GIFT_REDEMPTION = 'gift_redemption';

    public function __construct(
        private SmsService $sms,
        private MembershipService $membership,
    ) {}

    /**
     * @return array{token: string, user_name: string, offer_name: string, phone_masked: string|null, redemption_code: string}
     */
    public function search(Partner $partner, string $query): array
    {
        $giftRedemption = $this->findPendingGiftRedemption($partner, $query);

        if ($giftRedemption) {
            return $this->prepareGiftSearchResult($partner, $giftRedemption);
        }

        $claim = $this->findPendingClaim($partner, $query);

        if ($claim) {
            $offer = $claim->premiumOffer;
            if (! $offer || $offer->status !== PremiumOffer::STATUS_APPROVED || $offer->day_left <= 0) {
                throw new \InvalidArgumentException('ეს შეთავაზება ვადაგასულია ან არ არის აქტიური.');
            }

            return $this->prepareOfferSearchResult($partner, $claim, $offer);
        }

        $this->failSearchWithMessage($query);
    }

    private function failSearchWithMessage(string $query): never
    {
        $query = trim($query);

        if (preg_match('/^P-(\d+)$/i', $query) || preg_match('/^G-(\d+)$/i', $query)) {
            throw new \InvalidArgumentException('კოდი ვერ მოიძებნა ან უკვე გამოყენებულია.');
        }

        $user = $this->findUserByQuery($query);

        if (! $user) {
            throw new \InvalidArgumentException('სამწუხაროდ ნომერი ბაზაში არ არის.');
        }

        throw new \InvalidArgumentException(
            'მომხმარებელს არ აქვს გააქტიურებული შეთავაზება. გთხოვთ, მომხმარებელმა გააქტიუროს შეთავაზება.'
        );
    }

    private function findUserByQuery(string $query): ?User
    {
        $digits = preg_replace('/\D/', '', trim($query)) ?? '';

        if (strlen($digits) < 9) {
            return null;
        }

        $phone = PhoneNumber::normalize($digits);

        return User::query()->where('phone', $phone)->first();
    }

    /**
     * @return array{user_name: string, offer_name: string, p_coins_awarded: int}
     */
    public function verifyAndComplete(Partner $partner, string $token, string $code): array
    {
        $payload = $this->decodeScannerToken($token);

        if ((int) $payload['partner_id'] !== $partner->id) {
            throw new \InvalidArgumentException('არასწორი სესია.');
        }

        $type = $payload['type'] ?? self::TYPE_OFFER_CLAIM;
        $id = (int) ($payload['id'] ?? $payload['claim_id'] ?? 0);

        if ($type === self::TYPE_GIFT_REDEMPTION) {
            return $this->verifyAndCompleteGift($partner, $id, $code);
        }

        return $this->verifyAndCompleteOffer($partner, $id, $code);
    }

    public function findPendingClaim(Partner $partner, string $query): ?OfferClaim
    {
        $query = trim($query);
        $base = OfferClaim::query()
            ->with(['user', 'premiumOffer'])
            ->where('status', OfferClaim::STATUS_PENDING)
            ->whereHas('premiumOffer', fn ($q) => $q->where('partner_id', $partner->id));

        if (preg_match('/^P-(\d+)$/i', $query, $matches)) {
            return (clone $base)
                ->where('redemption_code', 'P-'.$matches[1])
                ->first();
        }

        if (preg_match('/^G-(\d+)$/i', $query)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $query) ?? '';
        if (strlen($digits) < 9) {
            return null;
        }

        $phone = PhoneNumber::normalize($digits);
        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return null;
        }

        if ($this->findPendingGiftRedemptionForUser($partner, $user)) {
            return null;
        }

        return (clone $base)
            ->where('user_id', $user->id)
            ->orderByDesc('claimed_at')
            ->first();
    }

    public function findPendingGiftRedemption(Partner $partner, string $query): ?GiftRedemption
    {
        $query = trim($query);

        if (preg_match('/^G-(\d+)$/i', $query, $matches)) {
            return GiftRedemption::query()
                ->with(['user', 'gift'])
                ->where('status', GiftRedemption::STATUS_PENDING)
                ->where('redemption_code', 'G-'.$matches[1])
                ->whereHas('gift', fn ($q) => $q->where('partner_id', $partner->id))
                ->first();
        }

        if (preg_match('/^P-(\d+)$/i', $query)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $query) ?? '';
        if (strlen($digits) < 9) {
            return null;
        }

        $phone = PhoneNumber::normalize($digits);
        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return null;
        }

        return $this->findPendingGiftRedemptionForUser($partner, $user);
    }

    /**
     * @return array{token: string, user_name: string, offer_name: string, phone_masked: string|null, redemption_code: string}
     */
    private function prepareOfferSearchResult(Partner $partner, OfferClaim $claim, PremiumOffer $offer): array
    {
        $verificationCode = $this->issueOfferVerificationCode($claim);
        $user = $claim->user;

        if ($user?->phone) {
            $this->sms->sendVerificationCode($user->phone, $verificationCode);
        } else {
            Log::info('Partner scanner verification (no user phone)', [
                'claim_id' => $claim->id,
                'redemption_code' => $claim->redemption_code,
                'verification_code' => $verificationCode,
            ]);
        }

        return [
            'token' => $this->createScannerToken(self::TYPE_OFFER_CLAIM, $claim->id, $partner),
            'user_name' => $user?->name ?? 'მომხმარებელი',
            'offer_name' => $offer->name,
            'phone_masked' => $user?->phone ? $this->maskPhone($user->phone) : null,
            'redemption_code' => $claim->redemption_code,
        ];
    }

    /**
     * @return array{token: string, user_name: string, offer_name: string, phone_masked: string|null, redemption_code: string}
     */
    private function prepareGiftSearchResult(Partner $partner, GiftRedemption $redemption): array
    {
        if ($redemption->isExpired()) {
            throw new \InvalidArgumentException('ეს საჩუქარი ვადაგასულია.');
        }

        $gift = $redemption->gift;
        $verificationCode = $this->issueGiftVerificationCode($redemption);
        $user = $redemption->user;

        if ($user?->phone) {
            $this->sms->sendVerificationCode($user->phone, $verificationCode);
        } else {
            Log::info('Partner scanner gift verification (no user phone)', [
                'gift_redemption_id' => $redemption->id,
                'redemption_code' => $redemption->redemption_code,
                'verification_code' => $verificationCode,
            ]);
        }

        return [
            'token' => $this->createScannerToken(self::TYPE_GIFT_REDEMPTION, $redemption->id, $partner),
            'user_name' => $user?->name ?? 'მომხმარებელი',
            'offer_name' => $gift?->name ?? 'საჩუქარი',
            'phone_masked' => $user?->phone ? $this->maskPhone($user->phone) : null,
            'redemption_code' => $redemption->redemption_code,
        ];
    }

    /**
     * @return array{user_name: string, offer_name: string, p_coins_awarded: int}
     */
    private function verifyAndCompleteOffer(Partner $partner, int $claimId, string $code): array
    {
        $claim = OfferClaim::query()
            ->with(['user', 'premiumOffer'])
            ->where('id', $claimId)
            ->where('status', OfferClaim::STATUS_PENDING)
            ->first();

        if (! $claim) {
            throw new \InvalidArgumentException('შეთავაზება უკვე გამოყენებულია ან ვერ მოიძებნა.');
        }

        $this->assertVerificationCode($claim->verification_code, $claim->verification_expires_at, $code);

        return DB::transaction(function () use ($partner, $claim) {
            $offer = $claim->premiumOffer;
            $user = $claim->user;
            $pCoins = $offer
                ? $this->membership->pCoinsForCardType($offer, $claim->card_type)
                : 0;

            $categoryId = $partner->categories()->first()?->id;

            Visit::create([
                'user_id' => $user->id,
                'partner_id' => $partner->id,
                'category_id' => $categoryId,
                'offer_claim_id' => $claim->id,
                'visited_at' => now(),
                'notes' => sprintf(
                    'შეთავაზება: %s | კოდი: %s | ფასდაკლება: %s%%',
                    $offer?->name ?? '—',
                    $claim->redemption_code,
                    $claim->discount_received
                ),
            ]);

            $claim->update([
                'status' => OfferClaim::STATUS_USED,
                'used_at' => now(),
                'verification_code' => null,
                'verification_expires_at' => null,
            ]);

            if ($pCoins > 0 && $user) {
                $user->increment('p_coins', $pCoins);
            }

            return [
                'user_name' => $user?->name ?? 'მომხმარებელი',
                'offer_name' => $offer?->name ?? 'შეთავაზება',
                'p_coins_awarded' => $pCoins,
            ];
        });
    }

    /**
     * @return array{user_name: string, offer_name: string, p_coins_awarded: int}
     */
    private function verifyAndCompleteGift(Partner $partner, int $redemptionId, string $code): array
    {
        $redemption = GiftRedemption::query()
            ->with(['user', 'gift'])
            ->where('id', $redemptionId)
            ->where('status', GiftRedemption::STATUS_PENDING)
            ->whereHas('gift', fn ($q) => $q->where('partner_id', $partner->id))
            ->first();

        if (! $redemption) {
            throw new \InvalidArgumentException('საჩუქარი უკვე გამოყენებულია ან ვერ მოიძებნა.');
        }

        if ($redemption->isExpired()) {
            throw new \InvalidArgumentException('ეს საჩუქარი ვადაგასულია.');
        }

        $this->assertVerificationCode($redemption->verification_code, $redemption->verification_expires_at, $code);

        return DB::transaction(function () use ($partner, $redemption) {
            $gift = $redemption->gift;
            $user = $redemption->user;
            $categoryId = $partner->categories()->first()?->id;

            Visit::create([
                'user_id' => $user->id,
                'partner_id' => $partner->id,
                'category_id' => $categoryId,
                'gift_redemption_id' => $redemption->id,
                'visited_at' => now(),
                'notes' => sprintf(
                    'საჩუქარი: %s | კოდი: %s',
                    $gift?->name ?? '—',
                    $redemption->redemption_code
                ),
            ]);

            $redemption->update([
                'status' => GiftRedemption::STATUS_USED,
                'used_at' => now(),
                'verification_code' => null,
                'verification_expires_at' => null,
            ]);

            return [
                'user_name' => $user?->name ?? 'მომხმარებელი',
                'offer_name' => $gift?->name ?? 'საჩუქარი',
                'p_coins_awarded' => 0,
            ];
        });
    }

    private function findPendingGiftRedemptionForUser(Partner $partner, User $user): ?GiftRedemption
    {
        return GiftRedemption::query()
            ->with(['user', 'gift'])
            ->where('user_id', $user->id)
            ->where('status', GiftRedemption::STATUS_PENDING)
            ->whereHas('gift', fn ($q) => $q->where('partner_id', $partner->id))
            ->orderByDesc('redeemed_at')
            ->first();
    }

    private function issueOfferVerificationCode(OfferClaim $claim): string
    {
        $code = $this->generateVerificationCode();

        $claim->update([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    private function issueGiftVerificationCode(GiftRedemption $redemption): string
    {
        $code = $this->generateVerificationCode();

        $redemption->update([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    private function generateVerificationCode(): string
    {
        return str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function assertVerificationCode(?string $storedCode, $expiresAt, string $code): void
    {
        if (! $storedCode || ! $expiresAt || $expiresAt->isPast()) {
            throw new \InvalidArgumentException('ვერიფიკაციის კოდი ვადაგასულია. ხელახლა ძებნა გააკეთეთ.');
        }

        if ($storedCode !== $code) {
            throw new \InvalidArgumentException('არასწორი ვერიფიკაციის კოდი.');
        }
    }

    private function createScannerToken(string $type, int $id, Partner $partner): string
    {
        return Crypt::encryptString(json_encode([
            'type' => $type,
            'id' => $id,
            'partner_id' => $partner->id,
            'exp' => now()->addMinutes(15)->timestamp,
        ]));
    }

    /**
     * @return array{type?: string, id?: int, claim_id?: int, partner_id: int, exp: int}
     */
    private function decodeScannerToken(string $token): array
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            throw new \InvalidArgumentException('არასწორი სესია.');
        }

        if (! isset($payload['partner_id'], $payload['exp'])) {
            throw new \InvalidArgumentException('არასწორი სესია.');
        }

        if (! isset($payload['id']) && ! isset($payload['claim_id'])) {
            throw new \InvalidArgumentException('არასწორი სესია.');
        }

        if ($payload['exp'] < now()->timestamp) {
            throw new \InvalidArgumentException('სესია ვადაგასულია. ხელახლა ძებნა გააკეთეთ.');
        }

        return $payload;
    }

    private function maskPhone(string $phone): string
    {
        $digits = PhoneNumber::display($phone);

        if (strlen($digits) < 6) {
            return Str::mask($digits, '*', 2);
        }

        return substr($digits, 0, 3).'***'.substr($digits, -3);
    }
}
