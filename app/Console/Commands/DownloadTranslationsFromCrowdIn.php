<?php

namespace App\Console\Commands;

use App\Services\CrowdInTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DownloadTranslationsFromCrowdIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-translations-from-crowdin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads translated content from crowdin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generator = new CrowdInTranslation;

        try {
            $this->output->info('Downloading translations...');
            $generator->downloadTranslations();

            $this->output->success('Done');
        } catch (\Exception $exception) {
            Log::error('Error while downloading from CrowdIn', [
                'exception' => $exception,
            ]);
            $this->output->error('An error occurred. See logs for details. '.$exception->getMessage());
        }
    }
}
