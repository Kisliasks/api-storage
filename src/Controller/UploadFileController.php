<?php

namespace App\Controller;

use App\DTO\File;
use App\Error\AbstractProblemJsonError;
use App\Error\ValidateErrorGenerator;
use App\Factory\FileConstraintFactory;
use App\Helpers\FileExtractor;
use App\Helpers\FileHelper;
use App\Interfaces\FileServiceInterface;
use App\Presenter\FilePresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validation;

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
        $file = $request->files->get('image');
        $filePath = $this->fileHelper->processFile($file);

        $requestData = $request->request->all();
        $requestData['file'] = $filePath;

        $validator = Validation::createValidator();
        $violations = $validator->validate($requestData, $this->constraintFactory->build());
        if ($violations->count() !== 0) {
            try {
                $this->exceptionGenerator->generate($violations);
            } catch (AbstractProblemJsonError $e) {
                if (is_readable($filePath)) {
                    unlink($filePath);
                }
                return new JsonResponse(
                    $e->jsonSerialize(),
                    $e->getCode()
                );
            } 
        }
        
        $file = new File(
            'some-uuid',
            '378509845',
            FileExtractor::extract($requestData)
        );
 
        return new JsonResponse(
            $this->filePresenter->present(
                $this->fileService->createFile($file)
            )
        );
    }
}