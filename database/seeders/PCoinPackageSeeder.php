<?php

namespace Database\Seeders;

use App\Models\PCoinPackage;
use Illuminate\Database\Seeder;

class PCoinPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => '50 P Coins',
                'p_coins' => 50,
                'price' => 10.00,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '100 P Coins',
                'p_coins' => 100,
                'price' => 20.00,
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '300 P Coins',
                'p_coins' => 300,
                'price' => 50.00,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '500 P Coins',
                'p_coins' => 500,
                'price' => 150.00,
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $package) {
            PCoinPackage::updateOrCreate(
                ['p_coins' => $package['p_coins']],
                $package
            );
        }
    }
}

