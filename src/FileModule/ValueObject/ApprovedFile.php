<?php

declare(strict_types=1);

namespace App\FileModule\ValueObject;

class ApprovedFile
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $filePath,
        private readonly string $fileExtension,
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
     * @return string
     */
    public function getFileUuid(): string
    {
        return $this->fileUuid;
    }
}