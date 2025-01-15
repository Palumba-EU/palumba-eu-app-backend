<?php

namespace App\Filament\Helper;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class SharedElectionFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        $sharedFilterValue = session('global_election', null);

        return parent::make('election')
            ->relationship('election', 'name')
            ->searchable()
            ->preload()
            ->default($sharedFilterValue)
            ->modifyQueryUsing(function ($data, Builder $query) {
                $value = $data['value'];
                session(['global_election' => $value]);
                if (is_null($value)) {
                    return $query;
                }

                return $query->election($value);
            });
    }
}
