<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Support\CategoryIcons;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'icon',
        'star',
        'small_text',
        'description',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'partner_category')
            ->withPivot('discount_percentage', 'points_per_visit')
            ->withTimestamps();
    }

    public function imageUrl(): ?string
    {
        return CategoryIcons::url($this->image);
    }

    public function selectOptionHtml(): string
    {
        return view('filament.components.category-select-option', [
            'name' => $this->name,
            'imageUrl' => $this->imageUrl(),
        ])->render();
    }
}
