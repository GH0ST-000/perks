<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PremiumOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'header_text',
        'description',
        'day_left',
        'discount',
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
            'is_premium' => 'boolean',
            'package_purchased' => 'boolean',
            'purchased_at' => 'datetime',
        ];
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
