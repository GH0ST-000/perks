<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'personal_number',
        'relationship',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
    public function getRelationshipNameAttribute(): string
    {
        return match($this->relationship) {
            'spouse' => 'მეუღლე',
            'child' => 'შვილი',
            'parent' => 'მშობელი',
            'sibling' => 'ძმა/და',
            'other' => 'სხვა',
            default => $this->relationship,
        };
    }
}

