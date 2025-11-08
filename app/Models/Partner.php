<?php

namespace App\Models;

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
}
