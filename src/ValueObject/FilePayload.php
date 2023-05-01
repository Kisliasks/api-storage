<?php

declare(strict_types=1);

namespace App\ValueObject;

class FilePayload
{
    public function __construct(
        private readonly string $fileName,
        private readonly int $fileSize,
        private readonly string $mimeType,
        private readonly string $fileExt,
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
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getFileExt(): string
    {
        return $this->fileExt;
    }
}