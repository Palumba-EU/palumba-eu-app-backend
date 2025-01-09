<?php

namespace App\Services\CrowdIn;

use Carbon\Carbon;

class TranslatableFile
{
    public function __construct(
        public readonly string $attributeName,
        public readonly string $fullPath,
        public readonly string $context,
        public readonly Carbon $updatedAt) {}
}
