<?php

declare(strict_types=1);

namespace App\FileModule\ValueObject;

class ParsedFileToken
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $fileUuid,
    ) {
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

     /**
     * @return string
     */
    public function getFileUuid(): string
    {
        return $this->fileUuid;
    }
}