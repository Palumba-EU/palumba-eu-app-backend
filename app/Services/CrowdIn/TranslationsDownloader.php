<?php

namespace App\Services\CrowdIn;

use CrowdinApiClient\Crowdin;
use CrowdinApiClient\Model\TranslationProjectBuild;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TranslationsDownloader
{
    public function __construct(
        private readonly Crowdin $client,
        private readonly int $projectId,
    ) {
        $this->disk = Storage::disk(config('crowdin.storage.disk'));
        $this->basePath = config('crowdin.storage.path');
    }

    public function run(): void
    {
        Log::debug('Downloading latest translations');
        $build = $this->getLatestBuild();

        $this->disk->makeDirectory('crowdin-tmp');
        $tmpFilename = $this->disk->path('crowdin-tmp/download.zip');
        $this->download($build->getId(), $tmpFilename);
        $this->unzip($tmpFilename);
    }

    private function getLatestBuild(): TranslationProjectBuild
    {
        /** @var TranslationProjectBuild|null $build */
        $build = Arr::first($this->client->translation->getProjectBuilds($this->projectId, ['limit' => 1]));

        if (is_null($build)) {
            Log::warning('No build to download available', ['project_id' => $this->projectId]);
            throw new \Exception('No build to download available');
        }

        return $build;
    }

    private function download(int $buildId, string $tmpFilename): void
    {
        $download = $this->client->translation->downloadProjectBuild($this->projectId, $buildId);

        $this->disk->makeDirectory(dirname($tmpFilename));

        file_put_contents($tmpFilename, file_get_contents($download->getUrl()));
    }

    private function unzip(string $filename)
    {
        $zip = new ZipArchive;
        $zip->open($filename);

        $this->disk->makeDirectory('translations');
        $targetPath = $this->disk->path('translations');

        $zip->extractTo($targetPath);
        $zip->close();
    }
}
