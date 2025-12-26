<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PCoinPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'p_coins',
        'price',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'p_coins' => 'integer',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

