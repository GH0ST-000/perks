<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var array<string, array{name?: string, image: string}>
     */
    private array $categories = [
        'ავტომობილი' => ['image' => 'images/categories/icons/avtomobili.png'],
        'განათლება' => ['image' => 'images/categories/icons/ganatleba.png'],
        'გართობა' => ['image' => 'images/categories/icons/gartoba.png'],
        'დასვენება' => ['image' => 'images/categories/icons/dasveneba.png'],
        'დეველოპმენტი' => ['name' => 'უძრავი ქონება', 'image' => 'images/categories/icons/udzravi-qoneba.png'],
        'უძრავი ქონება' => ['image' => 'images/categories/icons/udzravi-qoneba.png'],
        'თავის მოვლა' => ['image' => 'images/categories/icons/tavis-movla.png'],
        'კვება' => ['image' => 'images/categories/icons/kveba.png'],
        'კინო და ხელოვნება' => ['image' => 'images/categories/icons/kino-da-khelovneba.png'],
        'საბავშვო' => ['image' => 'images/categories/icons/sabavshvo.png'],
        'სახლი და რემონტი' => ['image' => 'images/categories/icons/sakhli-da-remonti.png'],
        'სპორტი და ფიტნესი' => ['image' => 'images/categories/icons/sporti-da-fitnesi.png'],
        'ტექნიკა' => ['image' => 'images/categories/icons/teknika.png'],
        'შინაური მეგობარი' => ['image' => 'images/categories/icons/shinauri-megobari.png'],
        'შოპინგი' => ['image' => 'images/categories/icons/shopingi.png'],
        'ჯანმრთელობა' => ['image' => 'images/categories/icons/janmrteloba.png'],
    ];

    public function up(): void
    {
        foreach ($this->categories as $matchName => $data) {
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
        // Icons are asset files; DB rollback is optional.
    }
};
