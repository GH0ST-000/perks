<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class CategoryIcons
{
    public static function isBundledPath(?string $path): bool
    {
        return is_string($path) && str_starts_with($path, 'images/categories/');
    }

    public static function exists(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (self::isBundledPath($path)) {
            return is_file(public_path($path));
        }

        return Storage::disk('public')->exists($path);
    }

    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (self::isBundledPath($path)) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @return array<string, array{label: string, url: string}>
     */
    public static function presets(): array
    {
        $icons = [];

        foreach (config('category_icons.presets', []) as $path => $label) {
            if (! self::exists($path)) {
                continue;
            }

            $icons[$path] = [
                'label' => $label,
                'url' => self::url($path),
            ];
        }

        return $icons;
    }

    public static function isPreset(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (array_key_exists($path, config('category_icons.presets', []))) {
            return true;
        }

        // Legacy storage paths from earlier migrations
        $basename = basename($path);
        foreach (config('category_icons.presets', []) as $presetPath => $label) {
            if (basename($presetPath) === $basename) {
                return true;
            }
        }

        return false;
    }

    public static function labelFor(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if ($label = config("category_icons.presets.{$path}")) {
            return $label;
        }

        $basename = basename($path);
        foreach (config('category_icons.presets', []) as $presetPath => $label) {
            if (basename($presetPath) === $basename) {
                return $label;
            }
        }

        return null;
    }

    public static function normalizeStoredPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (self::isBundledPath($path)) {
            return $path;
        }

        if (str_starts_with($path, 'categories/icons/')) {
            $bundled = 'images/categories/icons/'.basename($path);

            if (self::exists($bundled)) {
                return $bundled;
            }
        }

        return $path;
    }
}
