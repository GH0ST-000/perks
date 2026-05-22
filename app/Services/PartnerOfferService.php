<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PremiumOffer;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PartnerOfferService
{
    public function create(Partner $partner, array $data, ?UploadedFile $image = null): PremiumOffer
    {
        $discount = (float) $data['discount'];
        $expiresAt = $this->expiresAtFromPeriod($data['period']);

        $offer = new PremiumOffer([
            'partner_id' => $partner->id,
            'name' => $data['title'],
            'header_text' => $data['header_text'] ?? null,
            'description' => $data['description'],
            'standard_discount' => $discount,
            'premium_discount' => $discount,
            'discount' => $discount,
            'p_coins_reward' => (int) ($data['p_coins_reward'] ?? 0),
            'period' => $data['period'],
            'expires_at' => $expiresAt,
            'status' => PremiumOffer::STATUS_PENDING,
            'is_premium' => false,
        ]);

        if ($image) {
            $offer->image = $image->store('premium-offers', 'public');
        }

        $offer->save();

        return $offer;
    }

    public function update(PremiumOffer $offer, array $data, ?UploadedFile $image = null): PremiumOffer
    {
        $discount = (float) $data['discount'];

        $offer->fill([
            'name' => $data['title'],
            'header_text' => $data['header_text'] ?? null,
            'description' => $data['description'],
            'standard_discount' => $discount,
            'premium_discount' => $discount,
            'discount' => $discount,
            'p_coins_reward' => (int) ($data['p_coins_reward'] ?? 0),
            'period' => $data['period'],
            'expires_at' => $this->expiresAtFromPeriod($data['period']),
        ]);

        if ($offer->status !== PremiumOffer::STATUS_PENDING) {
            $offer->status = PremiumOffer::STATUS_PENDING;
            $offer->approved_at = null;
            $offer->rejection_reason = null;
        }

        if (! empty($data['remove_image'])) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $offer->image = null;
        } elseif ($image) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $offer->image = $image->store('premium-offers', 'public');
        }

        $offer->save();

        return $offer;
    }

    public function delete(PremiumOffer $offer): void
    {
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }

        $offer->delete();
    }

    public function expiresAtFromPeriod(string $period): Carbon
    {
        $expiresAt = match ($period) {
            '2 თვე' => now()->addMonths(2)->endOfDay(),
            '3 თვე' => now()->addMonths(3)->endOfDay(),
            default => now()->addMonth()->endOfDay(),
        };

        return Carbon::parse($expiresAt);
    }

    public function toPortalArray(PremiumOffer $offer): array
    {
        $status = $offer->status;
        if ($status === PremiumOffer::STATUS_APPROVED && $offer->day_left <= 0) {
            $status = 'expired';
        }

        return [
            'id' => $offer->id,
            'title' => $offer->name,
            'header_text' => $offer->header_text ?? '',
            'description' => $offer->description ?? '',
            'discount' => $offer->displayDiscount(),
            'p_coins' => (int) $offer->p_coins_reward,
            'period' => $offer->period ?? '1 თვე',
            'image' => $offer->image ? Storage::url($offer->image) : null,
            'status' => $status,
            'rejection_reason' => $offer->rejection_reason,
            'can_edit' => $offer->partnerCanEdit(),
            'can_delete' => $offer->partnerCanDelete(),
        ];
    }
}
