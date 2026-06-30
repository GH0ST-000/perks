<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Category::query()
            ->where('image', 'like', 'categories/icons/%')
            ->each(function (Category $category): void {
                $category->update([
                    'image' => 'images/categories/icons/'.basename($category->image),
                ]);
            });
    }

    public function down(): void
    {
        Category::query()
            ->where('image', 'like', 'images/categories/icons/%')
            ->each(function (Category $category): void {
                $category->update([
                    'image' => 'categories/icons/'.basename($category->image),
                ]);
            });
    }
};
