<?php

namespace App\Controller;

use App\DTO\File;
use App\Helpers\FileHelper;
use App\Helpers\FileExtractor;
use App\Presenter\FilePresenter;
use App\ValueObject\TempMovedFile;
use App\Error\ValidateErrorGenerator;
use App\Factory\FileConstraintFactory;
use App\Error\AbstractProblemJsonError;
use App\Interfaces\FileServiceInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFileController extends AbstractController
{
    public function __construct(
        private readonly FileConstraintFactory $constraintFactory,
        private readonly ValidateErrorGenerator $exceptionGenerator,
        private readonly FileHelper $fileHelper,
        private readonly FileServiceInterface $fileService,
        private readonly FilePresenter $filePresenter,
    ){

    }

    public function uploadFile(Request $request): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        /** @var TempMovedFile $tempMovedFile */
        $tempMovedFile = $this->fileHelper->processFile($file);

        $requestData = $request->request->all();
        $requestData['file'] = $tempMovedFile->getFilePath();

        $validator = Validation::createValidator();
        $violations = $validator->validate($requestData, $this->constraintFactory->build());
        if ($violations->count() !== 0) {
            try {
                $this->exceptionGenerator->generate($violations);
            } catch (AbstractProblemJsonError $e) {
                if (is_readable($tempMovedFile->getFilePath())) {
                    unlink($tempMovedFile->getFilePath());
                }
                return new JsonResponse(
                    $e->jsonSerialize(),
                    $e->getCode()
                );
            } 
        }
        $approvedFile = $this->fileHelper->processApprovedFile($tempMovedFile);       

        // добавить в модель путь к файлу и в базу колонку с путем
        $fileForDb = new File(
            $approvedFile->getFileUuid(),
            '378509845',
            FileExtractor::extract($requestData)
        );
 
        return new JsonResponse(
            $this->filePresenter->present(
                $this->fileService->createFile($fileForDb)
            )
        );
    }
}