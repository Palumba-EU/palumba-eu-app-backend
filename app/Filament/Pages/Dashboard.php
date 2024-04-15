<?php

namespace App\Filament\Pages;

use App\Jobs\DownloadTranslationsFromCrowdInJob;
use App\Jobs\UploadTranslationsToCrowdInJob;
use Filament\Actions\Action;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Download Translations')
                ->requiresConfirmation()
                ->action(fn () => DownloadTranslationsFromCrowdInJob::dispatch())
                ->authorize('sync crowdin'),
            Action::make('Upload Translations')
                ->requiresConfirmation()
                ->action(fn () => UploadTranslationsToCrowdInJob::dispatch())
                ->authorize('sync crowdin'),
        ];
    }
}
