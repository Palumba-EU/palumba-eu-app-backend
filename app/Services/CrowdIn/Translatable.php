<?php

namespace App\Services\CrowdIn;

interface Translatable
{
    /** Return a list of attributes that should be translated in CrowdIn */
    public function getTranslatableAttributes(): array;

    /** Returns the translated attribute */
    public function getTranslationForAttribute(string $attribute, ?string $language): ?string;

    /**
     * Return a list of file that should be translated
     *
     * @return array<TranslatableFile>
     */
    public function getTranslatableFiles(): array;

    /** Returns the url to the translated file */
    public function getTranslatedFile(string $filename, ?string $language): ?string;

    /** Return a unique identifier for the model and attribute */
    public function getIdentifier(string $attribute): string;

    /** Return a unique identifier for the model and file */
    public function getFileIdentifier(string $fullPath, string $attribute): string;

    /**
     * Return a list of relationships that should be loaded. (E.g. if necessary for context)
     *
     * @return array<string>
     */
    public static function getRelationshipsToEagerLoad(): array;
}
