<?php

namespace App\Services;

use App\Services\CrowdIn\CrowdInFileRepository;
use App\Services\CrowdIn\SourceImageUploader;
use App\Services\CrowdIn\SourceStringGenerator;
use CrowdinApiClient\Crowdin;
use Illuminate\Support\Collection;

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
}
