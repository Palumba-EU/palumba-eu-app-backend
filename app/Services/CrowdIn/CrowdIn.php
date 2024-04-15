<?php

namespace App\Services\CrowdIn;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait CrowdIn
{
    public static function bootCrowdIn()
    {
        self::created(function ($model) {
            Log::debug('Created', ['model' => $model]);
            // TODO schedule crowdin job
        });

        self::updated(function ($model) {
            Log::debug('Updated', ['model' => $model]);
            // TODO schedule crowdin job
        });
    }

    public function getIdentifier(string $attribute): string
    {
        return sprintf('%s-%s.%s', $this->getAttribute('id'), Str::kebab(class_basename(self::class)), $attribute);
    }

    public function getFileIdentifier(string $fullPath, string $attribute): string
    {
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        return sprintf('%s-%s.%s.%s', $this->getAttribute('id'), Str::kebab(class_basename(self::class)), $attribute, $extension);
    }

    public function getTranslationForAttribute(string $attribute, ?string $language = null): ?string
    {
        /** @var TranslationRepository $repo */
        $repo = resolve(TranslationRepository::class);

        return $repo->get(self::class, $this->getIdentifier($attribute), $this->getAttribute($attribute));
    }

    public function getTranslatedFile(string $filename, ?string $language = null): ?string
    {
        /** @var TranslationRepository $repo */
        $repo = resolve(TranslationRepository::class);
        $identifier = $this->getFileIdentifier($repo->disk->path($this->getAttribute($filename)), $filename);

        return $repo->getFile(self::class, $identifier, $this->getAttribute($filename));
    }
}
