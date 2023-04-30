<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enum\FileExpression;
use App\ValueObject\ApprovedFile;
use App\ValueObject\ParsedFileToken;
use App\ValueObject\TempMovedFile;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FileHelper
{
    private const MOCK_FILE_INFO = 'non-existent file info';
    private const APPROVED_FILE_PREFIX = 'approved_';
    private const FILE_STORAGE_DIRECTORY = '../files_storage';

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
}
