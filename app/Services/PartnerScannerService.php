<?php

namespace App\Services;

use App\Models\OfferClaim;
use App\Models\Partner;
use App\Models\PremiumOffer;
use App\Models\User;
use App\Models\Visit;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PartnerScannerService
{
    public function __construct(
        private SmsService $sms
    ) {}

    /**
     * @return array{token: string, user_name: string, offer_name: string, phone_masked: string|null, redemption_code: string}
     */
    public function search(Partner $partner, string $query): array
    {
        $claim = $this->findPendingClaim($partner, $query);

        if (! $claim) {
            throw new \InvalidArgumentException('შეთავაზება ვერ მოიძებნა ამ კოდით ან ტელეფონზე.');
        }

        $offer = $claim->premiumOffer;
        if (! $offer || $offer->status !== PremiumOffer::STATUS_APPROVED || $offer->day_left <= 0) {
            throw new \InvalidArgumentException('ეს შეთავაზება ვადაგასულია ან არ არის აქტიური.');
        }

        $verificationCode = $this->issueVerificationCode($claim);
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
            'token' => $this->createScannerToken($claim, $partner),
            'user_name' => $user?->name ?? 'მომხმარებელი',
            'offer_name' => $offer->name,
            'phone_masked' => $user?->phone ? $this->maskPhone($user->phone) : null,
            'redemption_code' => $claim->redemption_code,
        ];
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

        $claim = OfferClaim::query()
            ->with(['user', 'premiumOffer'])
            ->where('id', $payload['claim_id'])
            ->where('status', OfferClaim::STATUS_PENDING)
            ->first();

        if (! $claim) {
            throw new \InvalidArgumentException('შეთავაზება უკვე გამოყენებულია ან ვერ მოიძებნა.');
        }

        if (! $claim->verification_code || ! $claim->verification_expires_at || $claim->verification_expires_at->isPast()) {
            throw new \InvalidArgumentException('ვერიფიკაციის კოდი ვადაგასულია. ხელახლა ძებნა გააკეთეთ.');
        }

        if ($claim->verification_code !== $code) {
            throw new \InvalidArgumentException('არასწორი ვერიფიკაციის კოდი.');
        }

        return DB::transaction(function () use ($partner, $claim) {
            $offer = $claim->premiumOffer;
            $user = $claim->user;

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

            return [
                'user_name' => $user?->name ?? 'მომხმარებელი',
                'offer_name' => $offer?->name ?? 'შეთავაზება',
                'p_coins_awarded' => 0,
            ];
        });
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

        $digits = preg_replace('/\D/', '', $query) ?? '';
        if (strlen($digits) < 9) {
            return null;
        }

        $phone = PhoneNumber::normalize($digits);
        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            return null;
        }

        return (clone $base)
            ->where('user_id', $user->id)
            ->orderByDesc('claimed_at')
            ->first();
    }

    private function issueVerificationCode(OfferClaim $claim): string
    {
        $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $claim->update([
            'verification_code' => $code,
            'verification_expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    private function createScannerToken(OfferClaim $claim, Partner $partner): string
    {
        return Crypt::encryptString(json_encode([
            'claim_id' => $claim->id,
            'partner_id' => $partner->id,
            'exp' => now()->addMinutes(15)->timestamp,
        ]));
    }

    /**
     * @return array{claim_id: int, partner_id: int, exp: int}
     */
    private function decodeScannerToken(string $token): array
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            throw new \InvalidArgumentException('არასწორი სესია.');
        }

        if (! isset($payload['claim_id'], $payload['partner_id'], $payload['exp'])) {
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
