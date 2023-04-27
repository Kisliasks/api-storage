<?php

declare(strict_types=1);

namespace App\Helpers;

use App\ValueObject\ApprovedFile;
use App\ValueObject\TempMovedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FileHelper
{
    private const MOCK_FILE_PASS = 'non-existent file path';
    private const MOCK_FILE_NAME = 'non-existent file name';
    private const MOCK_FILE_EXT = 'non-existent file extension';
    private const APPROVED_FILE_PREFIX = 'approved_';

    public function processFile(?UploadedFile $file): TempMovedFile
    {
        if (!isset($file)) {
            $filePath = self::MOCK_FILE_PASS;
            $fileName = self::MOCK_FILE_NAME;
            $fileExtension = self::MOCK_FILE_NAME;
        } else {
            $fileName = uniqid('someFile');
            $fileExtension = "." . $file->getClientOriginalExtension();
            $filePath = '../files_storage' . DIRECTORY_SEPARATOR . $fileName . $fileExtension; 

            $file->move('../files_storage', $fileName . $fileExtension);
        }

        return new TempMovedFile($fileName, $filePath, $fileExtension);
    }

    public function processApprovedFile(TempMovedFile $tempMovedFile): ApprovedFile
    {
        $fileUuid = Uuid::v4()->toRfc4122();
        $newFileName = self::APPROVED_FILE_PREFIX . $fileUuid;

        $newFilePath = str_replace(
            $tempMovedFile->getFileName() . $tempMovedFile->getFileExtension(),
            $newFileName . $tempMovedFile->getFileExtension(),
            $tempMovedFile->getFilePath()
        );
        rename($tempMovedFile->getFilePath(), $newFilePath);

        return new ApprovedFile($newFileName, $newFilePath, $tempMovedFile->getFileExtension(), $fileUuid);
    }
}