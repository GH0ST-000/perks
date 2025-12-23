<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp_code',
        'type',
        'attempts',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if OTP is verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified(): void
    {
        $this->update([
            'verified_at' => now(),
        ]);
    }

    /**
     * Increment attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Scope for valid (not expired, not verified) OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
            ->whereNull('verified_at');
    }

    /**
     * Scope for specific phone and type
     */
    public function scopeForPhone($query, string $phone, string $type = 'login')
    {
        return $query->where('phone', $phone)
            ->where('type', $type);
    }
}

