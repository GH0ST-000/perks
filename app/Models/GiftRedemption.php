<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftRedemption extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_USED = 'used';

    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'gift_id',
        'p_coins_spent',
        'redemption_code',
        'verification_code',
        'verification_expires_at',
        'status',
        'notes',
        'redeemed_at',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'p_coins_spent' => 'integer',
        'redeemed_at' => 'datetime',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'verification_expires_at' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gift::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUsed(): bool
    {
        return $this->status === self::STATUS_USED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || ($this->expires_at && $this->expires_at->isPast());
    }

    public function redemptionCodeFor(): string
    {
        return 'G-'.$this->id;
    }
}
