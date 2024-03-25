<?php

namespace App\Services;

use App\Services\CrowdIn\Translatable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CrowdInTranslation
{
    private Filesystem $disk;

    private string $basePath;

    public function __construct()
    {
        $this->disk = Storage::disk(config('crowdin.storage.disk'));
        $this->basePath = config('crowdin.storage.path');
    }

    /**
     * Generates source language files for all registered models
     */
    public function generate(): void
    {
        $models = collect(config('crowdin.translatable_models'));
        $models->each(function ($class) {
            $data = $this->generateSourceStringsForClass($class);
            $filename = $this->getFilenameForClass($class);
            $this->disk->put($filename, json_encode($data->all()));
        });
    }

    private function generateSourceStringsForClass(string $class): Collection
    {
        return $class::query()->get()->flatMap(
            fn (Translatable $model) => $this->generateSourceStrings($model)
        );
    }

    private function generateSourceStrings(Translatable $model): Collection
    {
        $attributes = collect($model->getTranslatableAttributes());

        return $attributes->mapWithKeys(fn ($attribute) => [$model->getIdentifier($attribute) => $model->getAttribute($attribute)]);
    }

    private function getFilenameForClass(string $class): string
    {
        return sprintf('%s/%s.sources.json', $this->basePath, Str::kebab(class_basename($class)));
    }
}
