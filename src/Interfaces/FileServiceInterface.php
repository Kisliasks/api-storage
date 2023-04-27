<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\File;

interface FileServiceInterface
{
    public function createFile(File $file): File;

    public function getFileByUuid(string $uuid): File;
}