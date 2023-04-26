<?php

declare(strict_types=1);

namespace App\Helpers;

class FileExtractor
{
    public static function extract(array $payload): array
    {
        return [
            'file_name' => $payload['file_name'],
            'file_size' => $payload['file_size'],
            'mime_type' => $payload['mime_type']
        ];
    }
}
