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
                ->icon('heroicon-o-arrow-down-tray')
                ->label('Download Translations from CrowdIn')
                ->requiresConfirmation()
                ->modalHeading('Have you built the Translations?')
                ->modalDescription('Before downloading, you need to build the Translations in CrowdIn. Go to Tab Translations > Download as ZIP, select "All languages", hit "Build" and wait for the build to finish before continuing.')
                ->modalSubmitActionLabel('Yes, I built them. Continue')
                ->modalIcon('heroicon-o-arrow-down-tray')
                ->action(fn () => DownloadTranslationsFromCrowdInJob::dispatch())
                ->authorize('sync crowdin'),
            Action::make('Upload Translations')
                ->icon('heroicon-o-arrow-up-tray')
                ->label('Sync Source Texts to CrowdIn')
                ->requiresConfirmation()
                ->modalDescription('This will upload all english source texts to CrowdIn for translation')
                ->modalSubmitActionLabel('Yes, upload')
                ->modalIcon('heroicon-o-arrow-up-tray')
                ->action(fn () => UploadTranslationsToCrowdInJob::dispatch())
                ->authorize('sync crowdin'),
        ];
    }
}
