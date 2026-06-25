<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'description',
        'image',
        'p_coins_cost',
        'stock',
        'is_active',
        'sort_order',
        'type',
        'metadata',
    ];

    protected $casts = [
        'p_coins_cost' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function redemptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GiftRedemption::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function decreaseStock(int $quantity = 1): void
    {
        $this->decrement('stock', $quantity);
    }
}

