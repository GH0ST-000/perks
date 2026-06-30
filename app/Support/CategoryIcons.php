<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class CategoryIcons
{
    /**
     * @return array<string, array{label: string, url: string}>
     */
    public static function presets(): array
    {
        $icons = [];

        foreach (config('category_icons.presets', []) as $path => $label) {
            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $icons[$path] = [
                'label' => $label,
                'url' => Storage::disk('public')->url($path),
            ];
        }

        return $icons;
    }

    public static function isPreset(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        return array_key_exists($path, config('category_icons.presets', []));
    }

    public static function labelFor(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return config("category_icons.presets.{$path}");
    }
}
