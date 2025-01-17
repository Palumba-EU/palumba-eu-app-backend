<?php

namespace App\Filament\Helper;

use Filament\Forms\Components\Select;

class ElectionSelect extends Select
{
    public static function make(string $name = 'election_id'): static
    {
        return parent::make($name)
            ->relationship('election', 'name')
            ->preload()
            ->required()
            ->columnSpanFull()
            // Default to the currently set filter
            ->default(session('global_election'));
    }
}
