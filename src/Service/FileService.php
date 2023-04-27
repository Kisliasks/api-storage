<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\File;
use App\Entity\File as EntityFile;
use App\Interfaces\FileServiceInterface;
use App\Repository\FileRepository;
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
        $fileEntity->setFilePath($file->getLocalPath());

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($fileEntity);

        $entityManager->flush();

        return $fileEntity->toDto();
    }

    public function getFileByUuid(string $uuid): File
    {
        $fileFromDb = $this->fileRepository->findOneBy(['uuid' => $uuid]);
        if ($fileFromDb === null) {
            throw new EntityNotFoundException();
        }

        return $fileFromDb->toDto();
    }



}