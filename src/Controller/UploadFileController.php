<?php

namespace App\Controller;

use App\Factory\FileConstraintFactory;
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
    ){

    }

    public function uploadFile(Request $request): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('image');
        
        $fileName = uniqid('someFile');
        $fileName .= "." . $file->getClientOriginalExtension();
        $filePath = '../files_storage' . DIRECTORY_SEPARATOR . $fileName; 

        $file->move('../files_storage', $fileName);
        $requestData['file'] = $filePath;

        $validator = Validation::createValidator();
        $violations = $validator->validate($requestData, $this->constraintFactory->build());
        if ($violations->count() !== 0) {
            return new JsonResponse($violations->__toString());
        }

 
        return new Response('good');
    }
}