<?php

namespace App\Services\CrowdIn;

use CrowdinApiClient\Crowdin;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileInfo;

class SourceStringGenerator
{
    private Filesystem $disk;

    private string $basePath;

    public function __construct(
        private readonly Crowdin $client,
        private readonly CrowdInFileRepository $fileRepository,
        private readonly int $projectId,
        private readonly string $class,
    ) {
        $this->disk = Storage::disk(config('crowdin.storage.disk'));
        $this->basePath = config('crowdin.storage.path');
    }

    public function generateAndUpload(): void
    {
        try {
            $data = $this->generateSourceStringsForClass($this->class);
            $filename = $this->getFilenameForClass($this->class);
            $this->writeSourceFile($filename, $data);
            $this->uploadSourceFile($filename);
        } catch (\Exception $exception) {
            Log::error('Error while generating data for '.$this->class, [
                'exception' => $exception,
            ]);
        }
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

    private function writeSourceFile(string $filename, Collection $data): void
    {
        $this->disk->put($filename, json_encode($data->all()));
    }

    /**
     * @throws \Exception
     */
    private function uploadSourceFile(string $filename): void
    {
        $remoteFile = $this->fileRepository->getFileByName($filename);

        $filepath = $this->disk->path($filename);
        $storedFile = $this->client->storage->create(new SplFileInfo($filepath));

        if (is_null($storedFile)) {
            throw new \Exception('Unable to upload file to CrowdIn.', [
                'filename' => $filename,
            ]);
        }

        if (is_null($remoteFile)) {
            $this->client->file->create($this->projectId, [
                'storageId' => $storedFile->getId(),
                'type' => 'json',
                'name' => basename($filename),
            ]);
        } else {
            $this->client->file->update($this->projectId, $remoteFile->getId(), [
                'storageId' => $storedFile->getId(),
            ]);
        }

    }
}
