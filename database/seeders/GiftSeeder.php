<?php

namespace Database\Seeders;

use App\Models\Gift;
use Illuminate\Database\Seeder;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $gifts = [
            [
                'name' => 'Cavea Cinema Ticket',
                'description' => 'One standard ticket for any movie at Cavea Cinemas.',
                'image' => null,
                'p_coins_cost' => 50,
                'stock' => 100,
                'is_active' => true,
                'sort_order' => 1,
                'type' => 'voucher',
                'metadata' => json_encode([
                    'terms' => 'Valid for 3 months from redemption date',
                    'usage' => 'Show code at cinema ticket counter',
                ]),
            ],
            [
                'name' => 'Wolt 20₾ Voucher',
                'description' => 'Digital promo code for food delivery orders.',
                'image' => null,
                'p_coins_cost' => 100,
                'stock' => 50,
                'is_active' => true,
                'sort_order' => 2,
                'type' => 'voucher',
                'metadata' => json_encode([
                    'terms' => 'Valid for orders above 30₾',
                    'usage' => 'Apply promo code during checkout',
                ]),
            ],
            [
                'name' => 'Starbucks 15₾ Gift Card',
                'description' => 'Enjoy your favorite coffee on us with this gift card.',
                'image' => null,
                'p_coins_cost' => 75,
                'stock' => 30,
                'is_active' => true,
                'sort_order' => 3,
                'type' => 'voucher',
                'metadata' => json_encode([
                    'terms' => 'Valid at any Starbucks location in Georgia',
                    'usage' => 'Show code to cashier',
                ]),
            ],
            [
                'name' => 'Spotify Premium 1 Month',
                'description' => 'One month of ad-free music streaming with Spotify Premium.',
                'image' => null,
                'p_coins_cost' => 60,
                'stock' => 200,
                'is_active' => true,
                'sort_order' => 4,
                'type' => 'service',
                'metadata' => json_encode([
                    'terms' => 'Valid for new and existing Spotify accounts',
                    'usage' => 'Redeem code in Spotify app',
                ]),
            ],
            [
                'name' => 'Amazon 50₾ Gift Card',
                'description' => 'Shop your favorite products on Amazon with this gift card.',
                'image' => null,
                'p_coins_cost' => 250,
                'stock' => 20,
                'is_active' => true,
                'sort_order' => 5,
                'type' => 'voucher',
                'metadata' => json_encode([
                    'terms' => 'No expiration date',
                    'usage' => 'Apply code during Amazon checkout',
                ]),
            ],
            [
                'name' => 'Gym 1 Day Pass',
                'description' => 'Free one-day access to partner gym facilities.',
                'image' => null,
                'p_coins_cost' => 40,
                'stock' => 15,
                'is_active' => true,
                'sort_order' => 6,
                'type' => 'service',
                'metadata' => json_encode([
                    'terms' => 'Book in advance via phone',
                    'usage' => 'Show code at gym reception',
                ]),
            ],
        ];

        foreach ($gifts as $gift) {
            Gift::create($gift);
        }
    }
}

