<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\File;
use App\ValueObject\ParsedFileToken;

interface FileServiceInterface
{
    public function createFile(File $file): File;
    public function getFileByToken(ParsedFileToken $token): File;
    public function getFileByUuid(string $uuid): File;

}