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

    public function getIdentifier( string $attribute ): string {
        return sprintf('%s-%s.%s', Str::kebab(class_basename(self::class)), $this->getAttribute('id'), $attribute);
    }

}
