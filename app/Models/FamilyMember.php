<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'personal_number',
        'relationship',
        'status',
        'is_active',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (FamilyMember $member) {
            if (empty($member->status)) {
                $member->status = self::STATUS_PENDING;
                $member->is_active = false;
            }
        });
    }

    /**
     * Get the user that owns the family member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get localized relationship name.
     */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'დადასტურებული',
            self::STATUS_REJECTED => 'უარყოფილი',
            default => 'მოლოდინში',
        };
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getRelationshipNameAttribute(): string
    {
        return match ($this->relationship) {
            'spouse' => 'მეუღლე',
            'child' => 'შვილი',
            'parent' => 'მშობელი',
            'sibling' => 'ძმა/და',
            'other' => 'სხვა',
            default => $this->relationship,
        };
    }
}

