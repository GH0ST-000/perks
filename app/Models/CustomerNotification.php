<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_phone',
        'verification_code',
        'company_id',
        'manager_id',
        'discount',
        'usage_count',
        'status',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'usage_count' => 'integer',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByManager($query, int $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
