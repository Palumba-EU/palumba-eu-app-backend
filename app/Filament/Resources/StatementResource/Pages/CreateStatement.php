<?php

namespace App\Filament\Resources\StatementResource\Pages;

use App\Filament\Resources\StatementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStatement extends CreateRecord
{
    protected static string $resource = StatementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! array_key_exists('sort_index', $data)) {
            $data['sort_index'] = 0;
        }

        return $data;
    }
}
