<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var array<string, array{name?: string, image: string}>
     */
    private array $aliases = [
        'ჯამრთელობა' => ['name' => 'ჯანმრთელობა', 'image' => 'images/categories/icons/janmrteloba.png'],
        'სასტუმრო' => ['name' => 'დასვენება', 'image' => 'images/categories/icons/dasveneba.png'],
        'ფიტნესი' => ['name' => 'სპორტი და ფიტნესი', 'image' => 'images/categories/icons/sporti-da-fitnesi.png'],
    ];

    public function up(): void
    {
        foreach ($this->aliases as $matchName => $data) {
            $category = Category::query()->where('name', $matchName)->first();

            if (! $category) {
                continue;
            }

            $updates = ['image' => $data['image'], 'icon' => null];

            if (! empty($data['name'])) {
                $updates['name'] = $data['name'];
                $updates['slug'] = Str::slug($data['name']);
            }

            $category->update($updates);
        }
    }

    public function down(): void
    {
        //
    }
};
