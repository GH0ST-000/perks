<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'location',
        'city',
        'employment_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'experience_level',
        'department',
        'company_id',
        'is_active',
        'is_featured',
        'expires_at',
        'application_email',
        'application_url',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'expires_at' => 'date',
        ];
    }

    /**
     * Check if vacancy is expired
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->expires_at) {
                    return false;
                }

                return Carbon::parse($this->expires_at)->isPast();
            }
        );
    }

    /**
     * Get days left until expiration
     */
    protected function daysLeft(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->expires_at) {
                    return null;
                }

                $now = Carbon::now()->startOfDay();
                $expiresAt = Carbon::parse($this->expires_at)->startOfDay();

                $daysLeft = $now->diffInDays($expiresAt, false);

                return $daysLeft > 0 ? (int) $daysLeft : 0;
            }
        );
    }

    /**
     * Get formatted salary range
     */
    protected function salaryRange(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->salary_min && !$this->salary_max) {
                    return 'დასახელებულია';
                }

                $currency = $this->salary_currency ?? 'GEL';
                $symbol = $currency === 'USD' ? '$' : ($currency === 'EUR' ? '€' : '₾');

                if ($this->salary_min && $this->salary_max) {
                    $min = (float) ($this->salary_min ?? 0);
                    $max = (float) ($this->salary_max ?? 0);
                    return $symbol . number_format($min, 0) . ' - ' . $symbol . number_format($max, 0);
                } elseif ($this->salary_min) {
                    $min = (float) ($this->salary_min ?? 0);
                    return $symbol . number_format($min, 0) . '+';
                } elseif ($this->salary_max) {
                    $max = (float) ($this->salary_max ?? 0);
                    return 'up to ' . $symbol . number_format($max, 0);
                }

                return 'დასახელებულია';
            }
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Scope to get only active and non-expired vacancies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    /**
     * Scope to get featured vacancies
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}

