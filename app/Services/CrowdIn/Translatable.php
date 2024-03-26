<?php

namespace App\Services\CrowdIn;

interface Translatable
{
    /** Return a list of attributes that should be translated in CrowdIn */
    public function getTranslatableAttributes(): array;

    /**
     * Return a list of file that should be translated
     *
     * @return array<TranslatableFile>
     */
    public function getTranslatableFiles(): array;

    /** Return a unique identifier for the model and attribute */
    public function getIdentifier(string $attribute): string;
}
