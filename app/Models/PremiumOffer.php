<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class PremiumOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'header_text',
        'description',
        'day_left',
        'expires_at',
        'discount',
        'standard_discount',
        'premium_discount',
        'p_coins_reward',
        'partner_id',
        'is_premium',
        'package_purchased',
        'purchased_at',
        'purchased_by',
    ];

    protected function casts(): array
    {
        return [
            'discount' => 'decimal:2',
            'standard_discount' => 'decimal:2',
            'premium_discount' => 'decimal:2',
            'p_coins_reward' => 'integer',
            'is_premium' => 'boolean',
            'package_purchased' => 'boolean',
            'purchased_at' => 'datetime',
            'expires_at' => 'date',
        ];
    }

    /**
     * Calculate days left dynamically based on expires_at
     */
    protected function dayLeft(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->expires_at) {
                    return 0;
                }

                $now = Carbon::now()->startOfDay();
                $expiresAt = Carbon::parse($this->expires_at)->startOfDay();

                $daysLeft = $now->diffInDays($expiresAt, false);

                // Return 0 if expired (negative days)
                return $daysLeft > 0 ? (int) $daysLeft : 0;
            }
        );
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function purchasedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'purchased_by');
    }
}
