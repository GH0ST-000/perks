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

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

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
        'status',
        'period',
        'rejection_reason',
        'approved_at',
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
            'approved_at' => 'datetime',
        ];
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePublicVisible($query)
    {
        return $query->approved()->whereDate('expires_at', '>=', now());
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function partnerCanEdit(): bool
    {
        if ($this->status === self::STATUS_APPROVED && $this->day_left <= 0) {
            return false;
        }

        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
            self::STATUS_APPROVED,
        ], true);
    }

    public function partnerCanDelete(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_REJECTED,
        ], true);
    }

    public function displayDiscount(): int
    {
        return (int) round($this->standard_discount ?: $this->discount ?: 0);
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
