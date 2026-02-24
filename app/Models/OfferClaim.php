<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'premium_offer_id',
        'card_type',
        'discount_received',
        'status',
        'claimed_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_received' => 'decimal:2',
            'claimed_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function premiumOffer(): BelongsTo
    {
        return $this->belongsTo(PremiumOffer::class);
    }
}
