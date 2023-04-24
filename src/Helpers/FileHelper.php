<?php

declare(strict_types=1);

namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    private const MOCK_FILE_PASS = 'non-existent file path';

    public function processFile(?UploadedFile $file): string
    {
        if (!isset($file)) {
            $filePath = self::MOCK_FILE_PASS;
        } else {
            $fileName = uniqid('someFile');
            $fileName .= "." . $file->getClientOriginalExtension();
            $filePath = '../files_storage' . DIRECTORY_SEPARATOR . $fileName; 

            $file->move('../files_storage', $fileName);
        }

        return $filePath;
    }
}