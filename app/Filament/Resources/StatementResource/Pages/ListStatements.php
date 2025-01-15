<?php

namespace App\Filament\Resources\StatementResource\Pages;

use App\Filament\Resources\StatementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatements extends ListRecords
{
    protected static string $resource = StatementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
