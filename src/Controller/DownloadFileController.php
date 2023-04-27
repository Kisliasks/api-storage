<?php

namespace App\Controller;

use App\Helpers\FileHelper;
use App\Interfaces\FileServiceInterface;
use App\Presenter\FilePresenter;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFileController extends AbstractController
{
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly FilePresenter $filePresenter,
        private readonly FileHelper $fileHelper,
    )
    {

    }

    public function downloadFile(Request $request): Response
    {
        $fileToken = $request->attributes->get('token');

        try {
            $fileFromDb = $this->fileService->getFileByUuid($fileToken);
        } catch (EntityNotFoundException) {
            return new JsonResponse(
                null,
                JsonResponse::HTTP_NO_CONTENT
            );
        }
        
        $response = $this->fileHelper->generateDownloadFileResponse($fileFromDb);
        
        return $response;
    }
}