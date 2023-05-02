<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use App\Application\DTO\File;
use App\Application\Enum\FileConfig;
use App\Application\Enum\FileContentType;
use App\Application\Enum\FileExpression;
use App\FileModule\ValueObject\ApprovedFile;
use App\FileModule\ValueObject\FileFromStorage;
use App\FileModule\ValueObject\FilePayload;
use App\FileModule\ValueObject\ParsedFileToken;
use App\FileModule\ValueObject\TempMovedFile;
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
            $filesDirectory = self::getFilesDirectory();

            $filePath = $filesDirectory . DIRECTORY_SEPARATOR .
                $fileName . $fileExtension; 

            $file->move($filesDirectory, $fileName . $fileExtension);
        }

        return new TempMovedFile($fileName, $filePath, $fileExtension);
    }

    public function processApprovedFile(TempMovedFile $tempMovedFile): ApprovedFile
    {
        $fileUuid = Uuid::v4()->toRfc4122();
        $newLocalFileName = FileConfig::APPROVED_FILE_PREFIX . $fileUuid;

        $newFilePath = str_replace(
            $tempMovedFile->getFileName() . $tempMovedFile->getFileExtension(),
            $newLocalFileName . $tempMovedFile->getFileExtension(),
            $tempMovedFile->getFilePath(),
        );
        rename($tempMovedFile->getFilePath(), $newFilePath);

        return new ApprovedFile(
            $newLocalFileName,
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
        return self::getFilesDirectory() .
            DIRECTORY_SEPARATOR . FileConfig::APPROVED_FILE_PREFIX .
            $file->getUuid() . $file->getPayload()['file_extension'];
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
        $baseDomain = $_ENV['BASE_DOMAIN'];

        if ($_ENV['BASE_DOMAIN'] === '') {
            $baseDomain = FileConfig::DEV_BASE_DOMAIN;
        } 
        
        return $baseDomain . FileConfig::FILE_DOWNLOAD_ROUTE .
            $fileUuid . FileConfig::FILENAME_TOKEN_PREFIX . $fileName;
    }

    public static function generateFileContentType(FileFromStorage $file): string
    {
        $fileType = explode('/', $file->getMimeType());

        return match ($fileType[0]) {
            'image' => FileContentType::IMAGE->value,
            'application' => FileContentType::TEXT->value,
        };
    }

    private static function getFilesDirectory(): string
    {
        $filesDirectory = $_ENV['FILES_DIRECTORY'];
        if ($_ENV['FILES_DIRECTORY'] === '') {
            $filesDirectory = FileConfig::BASE_FILE_DIRECTORY;
        }

        return $filesDirectory;
    }
}
