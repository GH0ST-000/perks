<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'amount',
        'currency',
        'status',
        'bog_card_id',
        'payment_method_id',
        'current_period_start',
        'current_period_end',
        'next_billing_date',
        'cancelled_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'next_billing_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'expires_at' => $this->current_period_end ?? now(),
        ]);
    }

    public function renew(): void
    {
        $periodStart = $this->current_period_end ?? now();
        $periodEnd = $this->calculateNextPeriodEnd($periodStart);

        $this->update([
            'current_period_start' => $periodStart,
            'current_period_end' => $periodEnd,
            'next_billing_date' => $periodEnd,
        ]);
    }

    private function calculateNextPeriodEnd(Carbon $start): Carbon
    {
        return match ($this->type) {
            'monthly' => $start->copy()->addMonth(),
            'yearly' => $start->copy()->addYear(),
            default => $start->copy()->addMonth(),
        };
    }
}

