<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\File;
use App\Entity\File as EntityFile;
use App\Helpers\FileHelper;
use App\Interfaces\FileServiceInterface;
use App\Repository\FileRepository;
use App\ValueObject\FileFromStorage;
use App\ValueObject\ParsedFileToken;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class FileService implements FileServiceInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly FileRepository $fileRepository,
    )
    {

    }

    public function createFile(File $file): File
    {
        $fileEntity = new EntityFile();
        $fileEntity->setUuid($file->getUuid());
        $fileEntity->setAccountId($file->getAccountId());
        $fileEntity->setPayload($file->getPayload());
        $fileEntity->setDownloadLink($file->getDownloadLink());

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($fileEntity);

        $entityManager->flush();

        return $fileEntity->toDto();
    }

    public function getFileByToken(ParsedFileToken $token): File
    {
        $fileFromDb = $this->fileRepository->findOneBy(
            ['uuid' => $token->getFileUuid()]
        );
        if ($fileFromDb === null) {
            throw new EntityNotFoundException();
        }
        $fileNameFromDb = $fileFromDb->getPayload()['file_name'];
        if ($token->getFileName() !== $fileNameFromDb) {
            throw new EntityNotFoundException();
        }

        return $fileFromDb->toDto();
    }

    public function getFileByUuid(string $uuid): File
    {
        $fileFromDb = $this->fileRepository->findOneBy(
            ['uuid' => $uuid]
        );
        if ($fileFromDb === null) {
            throw new EntityNotFoundException();
        }

        return $fileFromDb->toDto();
    }

    public function getFileFromStorage(ParsedFileToken $token): FileFromStorage
    {
        $file = $this->getFileByToken($token);
        $filePath = FileHelper::generateStorageFilePath($file);
        if (!file_exists($filePath)) {
            throw new EntityNotFoundException();
        }

        return new FileFromStorage(
            $file->getPayload()['file_name'],
            $filePath,
            $file->getPayload()['mime_type'],
        );
    }
}