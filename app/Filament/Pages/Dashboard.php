<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'დაშბორდი';

    protected static ?string $title = 'დაშბორდი';

    protected static ?int $navigationSort = 0;

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
