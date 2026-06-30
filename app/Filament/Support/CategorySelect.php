<?php

namespace App\Filament\Support;

use App\Models\Category;
use Filament\Forms\Components\Select;

class CategorySelect
{
    public static function configure(Select $select): Select
    {
        return $select
            ->allowHtml()
            ->getOptionLabelFromRecordUsing(
                fn (Category $record): string => $record->selectOptionHtml()
            );
    }
}
