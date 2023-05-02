<?php

declare(strict_types=1);

namespace App\ValueObject;

class FileFromStorage
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $filePath,
        private readonly string $mimeType,
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
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
