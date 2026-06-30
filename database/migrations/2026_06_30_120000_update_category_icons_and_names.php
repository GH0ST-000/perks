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
        'ავტომობილი' => ['image' => 'categories/icons/avtomobili.png'],
        'განათლება' => ['image' => 'categories/icons/ganatleba.png'],
        'გართობა' => ['image' => 'categories/icons/gartoba.png'],
        'დასვენება' => ['image' => 'categories/icons/dasveneba.png'],
        'დეველოპმენტი' => ['name' => 'უძრავი ქონება', 'image' => 'categories/icons/udzravi-qoneba.png'],
        'უძრავი ქონება' => ['image' => 'categories/icons/udzravi-qoneba.png'],
        'თავის მოვლა' => ['image' => 'categories/icons/tavis-movla.png'],
        'კვება' => ['image' => 'categories/icons/kveba.png'],
        'კინო და ხელოვნება' => ['image' => 'categories/icons/kino-da-khelovneba.png'],
        'საბავშვო' => ['image' => 'categories/icons/sabavshvo.png'],
        'სახლი და რემონტი' => ['image' => 'categories/icons/sakhli-da-remonti.png'],
        'სპორტი და ფიტნესი' => ['image' => 'categories/icons/sporti-da-fitnesi.png'],
        'ტექნიკა' => ['image' => 'categories/icons/teknika.png'],
        'შინაური მეგობარი' => ['image' => 'categories/icons/shinauri-megobari.png'],
        'შოპინგი' => ['image' => 'categories/icons/shopingi.png'],
        'ჯანმრთელობა' => ['image' => 'categories/icons/janmrteloba.png'],
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
