<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Application\DTO\File;
use App\FileModule\ValueObject\FileFromStorage;
use App\FileModule\ValueObject\ParsedFileToken;

interface FileServiceInterface
{
    public function createFile(File $file): File;
    public function getFileByToken(ParsedFileToken $token): File;
    public function getFileByUuid(string $uuid): File;
    public function getFileFromStorage(ParsedFileToken $token): FileFromStorage;

}