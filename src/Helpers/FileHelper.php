<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTO\File;
use App\ValueObject\ApprovedFile;
use App\ValueObject\TempMovedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Uid\Uuid;

class FileHelper
{
    private const MOCK_FILE_INFO = 'non-existent file info';
    private const APPROVED_FILE_PREFIX = 'approved_';
    private const FILE_STORAGE_DIRECTORY = '../files_storage';
    private const CONTENT_TYPE_DOWNLOAD = 'image/gif';

    public function processFile(?UploadedFile $file): TempMovedFile
    {
        if (!isset($file)) {
            $filePath = self::MOCK_FILE_INFO;
            $fileName = self::MOCK_FILE_INFO;
            $fileExtension = self::MOCK_FILE_INFO;
        } else {
            $fileName = uniqid('someFile');
            $fileExtension = "." . $file->getClientOriginalExtension();
            $filePath = self::FILE_STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . $fileName . $fileExtension; 

            $file->move(self::FILE_STORAGE_DIRECTORY, $fileName . $fileExtension);
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

        return new ApprovedFile(
            $newFileName,
            $newFilePath,
            $tempMovedFile->getFileExtension(),
            $fileUuid
        );
    }

    public function generateDownloadFileResponse(File $file): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file->getLocalPath());
        $response->headers->set('Content-Type', self::CONTENT_TYPE_DOWNLOAD);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getPayload()['file_name']
        );

        return $response;
    }
}