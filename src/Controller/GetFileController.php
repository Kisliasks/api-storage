<?php

namespace App\Controller;

use App\Interfaces\FileServiceInterface;
use App\Presenter\FilePresenter;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFileController extends AbstractController
{
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly FilePresenter $filePresenter,
    )
    {

    }

    public function getFile(Request $request): Response
    {
        $fileId = $request->attributes->get('id');

        try {
            $response = $this->filePresenter->present(
                $this->fileService->getFileByUuid($fileId)
            );
            $statusCode = '200';
        } catch (EntityNotFoundException) {
            $response = null;
            $statusCode = '204';
        }
        
        return new JsonResponse($response, $statusCode);
    }
}