<?php

namespace App\Filament\Resources\LocalPartyResource\Pages;

use App\Filament\Resources\LocalPartyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocalParty extends EditRecord
{
    protected static string $resource = LocalPartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
