<?php

namespace App\Filament\Resources\CategoryResource\Pages\Concerns;

use App\Support\CategoryIcons;

trait HandlesCategoryIconForm
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (filled($data['image'] ?? null) && ! CategoryIcons::isPreset($data['image'])) {
            $data['custom_icon'] = $data['image'];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $custom = $data['custom_icon'] ?? null;

        if (filled($custom)) {
            $path = is_array($custom) ? reset($custom) : $custom;

            if (filled($path)) {
                $data['image'] = $path;
            }
        }

        unset($data['custom_icon']);

        return $data;
    }
}
