<?php

namespace App\Services\CrowdIn;

use Carbon\Carbon;
use CrowdinApiClient\Crowdin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SplFileInfo;

class SourceImageUploader
{
    public function __construct(
        private readonly Crowdin $client,
        private readonly CrowdInFileRepository $fileRepository,
        private readonly int $projectId,
        private readonly string $class,
    ) {}

    public function upload()
    {
        $this->class::query()->with($this->class::getRelationshipsToEagerLoad())->get()->each(function (Translatable $model) {
            /** @var Collection<TranslatableFile> $files */
            $files = collect($model->getTranslatableFiles());

            $files->each(function (TranslatableFile $file) use ($model) {
                try {
                    $directoryName = Str::kebab(class_basename($this->class));
                    $directory = $this->fileRepository->findOrCreateDirectory($directoryName);

                    $fileName = $model->getFileIdentifier($file->fullPath, $file->attributeName);
                    $remoteFile = $this->fileRepository->getFileByName($fileName, $directory->getId());

                    if (! is_null($remoteFile) && Carbon::make($remoteFile->getUpdatedAt())->isAfter($file->updatedAt)) {
                        Log::debug('Skipping "'.$file->fullPath.'": remote file is newer');

                        return;
                    }

                    $storedFile = $this->client->storage->create(new SplFileInfo($file->fullPath));

                    if (is_null($storedFile)) {
                        throw new \Exception('Unable to upload file to storage.');
                    }

                    if (is_null($remoteFile)) {
                        $this->client->file->create($this->projectId, [
                            'storageId' => $storedFile->getId(),
                            'type' => 'auto',
                            'name' => $fileName,
                            'title' => $file->context,
                            'context' => $file->context,
                            'directoryId' => $directory->getId(),
                        ]);
                    } else {
                        $this->client->file->update($this->projectId, $remoteFile->getId(), [
                            'storageId' => $storedFile->getId(),
                        ]);
                    }
                } catch (\Exception $exception) {
                    Log::error('Error while uploading file', [
                        'file' => $file,
                    ]);
                }
            });
        });
    }
}
