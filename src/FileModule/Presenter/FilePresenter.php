<?php

declare(strict_types=1);

namespace App\FileModule\Presenter;

use App\Application\DTO\File;

class FilePresenter
{
    public function presentToHttp(File $file): array
    {
        $payload = $file->getPayload();
        return [
            'file' => [
                'uuid' => $file->getUuid(),
                'file_name' => $payload['file_name'],
                'file_size' => $payload['file_size'],
                'mime_type' => $payload['mime_type'],
                'file_extension' => $payload['file_extension'],
                'download_link' => $file->getDownloadLink(),
            ]
        ];
    }
}
