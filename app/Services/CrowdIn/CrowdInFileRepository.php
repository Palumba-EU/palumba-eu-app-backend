<?php

namespace App\Services\CrowdIn;

use CrowdinApiClient\Crowdin;
use CrowdinApiClient\Model\Directory;
use CrowdinApiClient\Model\File;
use CrowdinApiClient\ModelCollection;

class CrowdInFileRepository
{
    /** @var array<int, ?ModelCollection> */
    private array $files = [];

    private ?ModelCollection $directories = null;

    public function __construct(private readonly Crowdin $client, private readonly int $projectId)
    {

    }

    public function getFileByName(string $filename, ?int $directoryId = null): ?File
    {
        if (! array_key_exists($directoryId, $this->files) || is_null($this->files[$directoryId])) {
            $this->files[$directoryId] = $this->client->file->list($this->projectId, ['directoryId' => $directoryId, 'limit' => 250]);
        }

        foreach ($this->files[$directoryId] as $file) {
            /** @var File $file */
            if ($file->getName() === basename($filename)) {
                return $file;
            }
        }

        return null;
    }

    public function findOrCreateDirectory(string $name): Directory
    {
        if (is_null($this->directories)) {
            $this->directories = $this->client->directory->list($this->projectId, ['limit' => 250]);
        }

        foreach ($this->directories as $directory) {
            /** @var Directory $directory */
            if ($directory->getName() === $name) {
                return $directory;
            }
        }

        $directory = $this->client->directory->create($this->projectId, [
            'name' => $name,
        ]);

        // Force refetch next time
        $this->directories = null;

        return $directory;
    }
}
