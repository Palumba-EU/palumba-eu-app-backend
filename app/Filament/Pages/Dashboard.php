<?php

namespace App\Filament\Pages;

use App\Jobs\UploadTranslationsToCrowdInJob;
use Filament\Actions\Action;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Upload Translations')
                ->requiresConfirmation()
                ->action(fn () => UploadTranslationsToCrowdInJob::dispatch()),
        ];
    }
}
