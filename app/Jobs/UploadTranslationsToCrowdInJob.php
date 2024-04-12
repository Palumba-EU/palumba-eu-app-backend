<?php

namespace App\Jobs;

use App\Services\CrowdInTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadTranslationsToCrowdInJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public bool $uploadStrings = true, public bool $uploadFiles = true)
    {
        //
    }

    public function handle(): void
    {
        $generator = new CrowdInTranslation();

        if ($this->uploadFiles) {
            $generator->uploadStrings();
        }

        if ($this->uploadStrings) {
            $generator->uploadFiles();
        }
    }
}
