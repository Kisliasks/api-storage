<?php

namespace App\Controller;

use App\Error\AbstractProblemJsonError;
use App\Error\ValidateErrorGenerator;
use App\Factory\FileConstraintFactory;
use App\Helpers\FileHelper;
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
        

 
        return new JsonResponse('good');
    }
}