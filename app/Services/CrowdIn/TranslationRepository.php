<?php

namespace App\Services\CrowdIn;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TranslationRepository
{
    public Filesystem $disk;

    /** @var array caches loaded and parsed files */
    private array $files = [];

    public function __construct(private ?string $language)
    {
        $this->disk = Storage::disk(config('crowdin.storage.disk'));

        if (preg_match('/^[a-z]{2,3}(-[A-Z]{2})?$/m', $this->language) !== 1) {
            throw new \Exception('Invalid language code');
        }

        if ($this->language === 'en') {
            $this->language = null;
        }
    }

    public function get(string $class, string $identifier, mixed $default): ?string
    {
        if (is_null($this->language)) {
            return $default;
        }

        $path = $this->getPath($class);
        $data = $this->getData($path);

        if (array_key_exists($identifier, $data)) {
            return $data[$identifier];
        }

        return $default;
    }

    private function getPath(string $class): string
    {
        $filename = sprintf('%s.sources.json', Str::kebab(class_basename($class)));

        return sprintf('translations/%s/%s', $this->language, $filename);
    }

    private function getData(string $path): array
    {
        if (! array_key_exists($path, $this->files)) {
            if (! $this->disk->exists($path)) {
                throw new \Exception('Translation does not exist');
            }

            $this->files[$path] = json_decode($this->disk->get($path), true);
        }

        return $this->files[$path];
    }

    public function getFile(string $class, string $identifier, mixed $default): ?string
    {
        $directoryName = Str::kebab(class_basename($class));
        $translationPath = sprintf('translations/%s/%s/%s', $this->language, $directoryName, $identifier);

        $path = $this->disk->exists($translationPath)
            ? $translationPath
            : $default;

        if (is_null($path)) {
            return null;
        }

        return $this->disk->url($path);
    }
}
