<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tag_text',
        'headline_before',
        'headline_highlight',
        'headline_after',
        'sub_headline',
        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link',
        'background_image',
        'order',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(SliderImage::class)->orderBy('order');
    }
}
