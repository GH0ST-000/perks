<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gift_id',
        'p_coins_spent',
        'redemption_code',
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
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || ($this->expires_at && $this->expires_at->isPast());
    }
}

