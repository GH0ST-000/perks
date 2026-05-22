<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferClaim extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_USED = 'used';

    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'premium_offer_id',
        'card_type',
        'discount_received',
        'status',
        'redemption_code',
        'verification_code',
        'verification_expires_at',
        'claimed_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_received' => 'decimal:2',
            'claimed_at' => 'datetime',
            'used_at' => 'datetime',
            'verification_expires_at' => 'datetime',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function visit(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Visit::class);
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
