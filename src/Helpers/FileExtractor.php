<?php

declare(strict_types=1);

namespace App\Helpers;

use App\ValueObject\FilePayload;

class FileExtractor
{
    public static function extract(FilePayload $payload): array
    {
        return [
            'file_name' => $payload->getFileName(),
            'file_size' => $payload->getFileSize(),
            'mime_type' => $payload->getMimeType(),
            'file_extension' => $payload->getFileExt(),
        ];
    }
}
