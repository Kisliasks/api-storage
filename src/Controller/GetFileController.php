<?php

namespace App\Controller;

use App\Helpers\FileResponse;
use App\Interfaces\FileServiceInterface;
use App\Presenter\FilePresenter;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            return FileResponse::httpAcceptedResponse(
                $this->filePresenter->present(
                    $this->fileService->getFileByUuid($fileId)
                )
            );
        } catch (EntityNotFoundException) {
            return FileResponse::httpNotFoundResponse();
        }
    }
}