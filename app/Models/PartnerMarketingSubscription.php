<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerMarketingSubscription extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_PAST_DUE = 'past_due';

    protected $fillable = [
        'partner_id',
        'user_id',
        'package_id',
        'package_title',
        'amount',
        'currency',
        'status',
        'bog_card_id',
        'payment_method_id',
        'started_at',
        'last_billed_at',
        'current_period_start',
        'current_period_end',
        'next_billing_date',
        'cancelled_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'started_at' => 'datetime',
            'last_billed_at' => 'datetime',
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'next_billing_date' => 'datetime',
            'cancelled_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BogPayment::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);
    }

    public function activate(string $bogCardId, ?int $paymentMethodId = null): void
    {
        $periodEnd = now()->addMonth();

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'bog_card_id' => $bogCardId,
            'payment_method_id' => $paymentMethodId,
            'started_at' => now(),
            'last_billed_at' => now(),
            'current_period_start' => now(),
            'current_period_end' => $periodEnd,
            'next_billing_date' => $periodEnd,
        ]);
    }

    public function renewBillingPeriod(): void
    {
        $periodStart = $this->current_period_end ?? now();
        $periodEnd = Carbon::parse($periodStart)->addMonth();

        $this->update([
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
            'next_billing_date' => $periodEnd,
            'last_billed_at' => now(),
            'status' => self::STATUS_ACTIVE,
        ]);
    }
}
