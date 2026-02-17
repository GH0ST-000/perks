<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BogPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'external_order_id',
        'bog_order_id',
        'type',
        'amount',
        'currency',
        'status',
        'payment_method',
        'description',
        'card_id',
        'subscription_id',
        'bog_response',
        'callback_data',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bog_response' => 'array',
        'callback_data' => 'array',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}

