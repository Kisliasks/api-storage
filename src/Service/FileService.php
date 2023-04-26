<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\File;
use App\Entity\File as EntityFile;
use App\Interfaces\FileServiceInterface;
use Doctrine\Persistence\ManagerRegistry;

class FileService implements FileServiceInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine
    )
    {

    }

    public function createFile(File $file): File
    {
        $fileEntity = new EntityFile();
        $fileEntity->setUuid($file->getUuid());
        $fileEntity->setAccountId($file->getAccountId());
        $fileEntity->setPayload($file->getPayload());

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($fileEntity);

        $entityManager->flush();

        return $fileEntity->toDto();
    }



}