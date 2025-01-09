<?php

namespace App\Console\Commands;

use App\Services\CrowdInTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UploadTranslationsToCrowdIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-translations-to-crowdin {--strings} {--files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates translations and upload it together with images to crowdin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generator = new CrowdInTranslation();

        $generateStrings = $this->option('strings');
        $generateFiles = $this->option('files');

        if (! $generateStrings && ! $generateFiles) {
            $generateStrings = $generateFiles = true;
        }

        try {
            if ($generateStrings) {
                $this->output->info('Generating strings...');
                $generator->uploadStrings();
            }

            if ($generateFiles) {
                $this->output->info('Generating files...');
                $generator->uploadFiles();
            }

            $this->output->success('Done');
        } catch (\Exception $exception) {
            Log::error('Error while generating and upload to CrowdIn', [
                'exception' => $exception,
            ]);
            $this->output->error('An error occurred. See logs for details. '.$exception->getMessage());
        }
    }
}
