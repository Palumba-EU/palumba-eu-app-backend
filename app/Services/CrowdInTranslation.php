<?php

namespace App\Services;

use App\Services\CrowdIn\CrowdInFileRepository;
use App\Services\CrowdIn\SourceImageUploader;
use App\Services\CrowdIn\SourceStringGenerator;
use App\Services\CrowdIn\TranslationsDownloader;
use CrowdinApiClient\Crowdin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CrowdInTranslation
{
    private Collection $models;

    private Crowdin $client;

    private int $projectId;

    private CrowdInFileRepository $fileRepository;

    public function __construct()
    {
        $this->models = collect(config('crowdin.translatable_models'));
        $this->projectId = config('crowdin.project_id');
        $this->client = resolve(Crowdin::class);
        $this->fileRepository = new CrowdInFileRepository($this->client, $this->projectId);
    }

    /**
     * Generates source language files for all registered models
     */
    public function uploadStrings(): void
    {
        $this->models->each(function (string $class) {
            $generator = new SourceStringGenerator($this->client, $this->fileRepository, $this->projectId, $class);
            $generator->generateAndUpload();
        });
    }

    /**
     * Collects and uploads all files that need translation
     */
    public function uploadFiles(): void
    {
        $this->models->each(function (string $class) {
            $uploader = new SourceImageUploader($this->client, $this->fileRepository, $this->projectId, $class);
            $uploader->upload();
        });
    }

    public function downloadTranslations(): void
    {
        $downloader = new TranslationsDownloader($this->client, $this->projectId);
        $downloader->run();
    }

    public function listTargetLanguages(): Collection
    {
        $languages = [];

        try {
            $languages = Cache::remember('languages', config('crowdin.target_language_cache_time'), function () {
                $project = $this->client->project->get($this->projectId);

                return collect($project->getTargetLanguages())->map(fn ($language) => ([
                    'id' => $language['id'], // kept for backwards compatibility
                    'name' => $language['name'],
                    'language_code' => $language['id'],
                ]));
            });
        } catch (\Exception $exception) {
            Log::error('Error while loading target languages from crowdin', ['exception' => $exception]);
        }

        return $languages;
    }
}
