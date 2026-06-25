<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'offer_claim_id',
        'gift_redemption_id',
        'category_id',
        'visited_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function offerClaim(): BelongsTo
    {
        return $this->belongsTo(OfferClaim::class);
    }

    public function giftRedemption(): BelongsTo
    {
        return $this->belongsTo(GiftRedemption::class);
    }
}
