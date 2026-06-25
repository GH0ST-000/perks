<?php

namespace App\Models;

use App\Services\PartnerAccountService;
use App\Services\PartnerOfferService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'logo',
        'business_info',
        'website',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Partner $partner): void {
            $offerService = app(PartnerOfferService::class);

            $partner->premiumOffers()->each(
                fn (PremiumOffer $offer) => $offerService->delete($offer)
            );

            app(PartnerAccountService::class)->removeLoginUser($partner);
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'partner_category')
            ->withPivot('discount_percentage', 'points_per_visit')
            ->withTimestamps();
    }

    public function premiumOffers(): HasMany
    {
        return $this->hasMany(PremiumOffer::class);
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(Gift::class);
    }

    public function loginUser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class)->where('role', 'partner');
    }

    public function marketingSubscriptions(): HasMany
    {
        return $this->hasMany(PartnerMarketingSubscription::class);
    }

    public function activeMarketingSubscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PartnerMarketingSubscription::class)
            ->where('status', PartnerMarketingSubscription::STATUS_ACTIVE)
            ->latest();
    }
}

