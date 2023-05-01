<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTO\File;
use App\Enum\FileConfig;
use App\Enum\FileExpression;
use App\ValueObject\ApprovedFile;
use App\ValueObject\FilePayload;
use App\ValueObject\ParsedFileToken;
use App\ValueObject\TempMovedFile;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FileHelper
{
    public function processFile(?UploadedFile $file): TempMovedFile
    {
        if (!isset($file)) {
            $filePath = FileConfig::MOCK_FILE_INFO;
            $fileName = FileConfig::MOCK_FILE_INFO;
            $fileExtension = FileConfig::MOCK_FILE_INFO;
        } else {
            $fileName = uniqid('someFile');
            $fileExtension = "." . $file->getClientOriginalExtension();
            $filePath = FileConfig::BASE_FILE_DIRECTORY . DIRECTORY_SEPARATOR . $fileName . $fileExtension; 

            $file->move(FileConfig::BASE_FILE_DIRECTORY, $fileName . $fileExtension);
        }

        return new TempMovedFile($fileName, $filePath, $fileExtension);
    }

    public function processApprovedFile(TempMovedFile $tempMovedFile): ApprovedFile
    {
        $fileUuid = Uuid::v4()->toRfc4122();
        $newFileName = FileConfig::APPROVED_FILE_PREFIX . $fileUuid;

        $newFilePath = str_replace(
            $tempMovedFile->getFileName() . $tempMovedFile->getFileExtension(),
            $newFileName . $tempMovedFile->getFileExtension(),
            $tempMovedFile->getFilePath(),
        );
        rename($tempMovedFile->getFilePath(), $newFilePath);

        return new ApprovedFile(
            $newFileName,
            $newFilePath,
            $tempMovedFile->getFileExtension(),
            $fileUuid,
        );
    }

    /**
     * @param string $token
     * @return ParsedFileToken
     * @throws Exception
     */
    public function parseFileTokenFromRequest(string $token): ParsedFileToken
    {
        if (
            !preg_match(
                FileExpression::FILE_TOKEN_EXPRESSION->value,
                $token,
                $matches
            )
        ) {
            throw new Exception('Invalid file token');
        } 
        $fileName = $matches[2];
        $fileUuid = $matches[1];

        return new ParsedFileToken(
            $fileName,
            $fileUuid,
        );
    }

    public static function generateStorageFilePath(File $file): string
    {
        $filePath = FileConfig::BASE_FILE_DIRECTORY . DIRECTORY_SEPARATOR .
        FileConfig::APPROVED_FILE_PREFIX . $file->getUuid() . $file->getPayload()['file_extension'];

        return $filePath;
    }

    public function generateFilePayload(string $fileName, ApprovedFile $approvedFile): FilePayload
    {
        if (file_exists($localFilePath = $approvedFile->getFilePath())) {
            $mimeType = mime_content_type($localFilePath);
            $fileSize = filesize($localFilePath);
            $fileExt = $approvedFile->getFileExtension();
        }

        return new FilePayload(
            $fileName,
            $fileSize,
            $mimeType,
            $fileExt,
        );
    }

    public function generateDownloadLink(string $fileName, string $fileUuid): string
    {
       $downloadLink = 'http://' . $_SERVER['SERVER_NAME'] . '/api/files/download/' . $fileUuid .
       '&name=' . $fileName;

       return $downloadLink;
    }
}
