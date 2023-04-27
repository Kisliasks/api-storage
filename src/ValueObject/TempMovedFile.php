<?php

declare(strict_types=1);

namespace App\ValueObject;

class TempMovedFile
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $filePath,
        private readonly string $fileExtension
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
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        is_readable($this->filePath) ? unlink($this->filePath) : null;
    }
}