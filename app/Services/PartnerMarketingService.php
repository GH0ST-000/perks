<?php

namespace App\Services;

class PartnerMarketingService
{
    /**
     * @return list<array<string, mixed>>
     */
    public function packages(): array
    {
        return [
            [
                'id' => 'social',
                'title' => 'სოციალური გაძლიერება',
                'price' => 300,
                'icon' => 'zap',
                'icon_bg' => 'bg-orange-50 dark:bg-orange-900/20',
                'icon_color' => 'text-orange-500',
                'features' => [
                    'Facebook რეკლამა (7 დღე)',
                    'Instagram პოსტი',
                    'ძირითადი ანალიტიკა',
                ],
                'featured' => false,
                'button' => 'finish',
            ],
            [
                'id' => 'platinum',
                'title' => 'პლატინის პარტნიორი',
                'price' => 800,
                'icon' => 'crown',
                'icon_bg' => 'bg-blue-50 dark:bg-blue-900/20',
                'icon_color' => 'text-blue-600 dark:text-blue-400',
                'features' => [
                    'პრემიუმ განთავსება',
                    'VIP მხარდაჭერა',
                    'პერსონალური მენეჯერი',
                    'ყოველკვირეული ანგარიშები',
                    'ინდივიდუალური პანელები',
                ],
                'featured' => true,
                'badge' => 'საუკეთესო არჩევანი',
                'button' => 'primary',
            ],
            [
                'id' => 'executive',
                'title' => 'აღმასრულებელი დონე',
                'price' => 1500,
                'icon' => 'shield-check',
                'icon_bg' => 'bg-purple-50 dark:bg-purple-900/20',
                'icon_color' => 'text-purple-600 dark:text-purple-400',
                'features' => [
                    'ლოგო ღონისძიებებზე',
                    'საგამოფენო სივრცე',
                    '10 VIP საშვი',
                    'უვადო პრივილეგიები',
                ],
                'featured' => false,
                'button' => 'finish',
            ],
        ];
    }

    public function packageTitle(string $id): ?string
    {
        foreach ($this->packages() as $package) {
            if ($package['id'] === $id) {
                return $package['title'];
            }
        }

        return null;
    }
}
