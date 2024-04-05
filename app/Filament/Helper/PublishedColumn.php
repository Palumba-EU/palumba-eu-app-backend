<?php

namespace App\Filament\Helper;

use Filament\Tables\Columns\IconColumn;

class PublishedColumn extends IconColumn
{
    public static function make(string $name): static
    {
        return parent::make($name)->boolean()
            ->trueIcon('heroicon-o-check-circle')
            ->falseIcon('heroicon-o-pencil-square')
            ->falseColor('warning');
    }
}
