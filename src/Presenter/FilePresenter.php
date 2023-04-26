<?php

declare(strict_types=1);

namespace App\Presenter;

use App\DTO\File;

class FilePresenter
{
    public function present(File $file): array
    {
        $payload = $file->getPayload();
        return [
            'file' => [
                'uuid' => $file->getUuid(),
                'file_name' => $payload['file_name'],
                'file_size' => $payload['file_size'],
                'mime_type' => $payload['mime_type'],
            ]
        ];
    }
}
