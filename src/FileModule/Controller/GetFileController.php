<?php

namespace App\FileModule\Controller;

use App\Application\Helpers\FileResponse;
use App\Application\Interfaces\FileServiceInterface;
use App\FileModule\Presenter\FilePresenter;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFileController extends AbstractController
{
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly FilePresenter $filePresenter,
    ) {
    }

    public function getFile(Request $request): Response
    {
        $fileId = $request->attributes->get('id');

        try {
            return FileResponse::httpOkResponse(
                $this->filePresenter->presentToHttp(
                    $this->fileService->getFileByUuid($fileId)
                )
            );
        } catch (EntityNotFoundException) {
            return FileResponse::httpNotFoundResponse();
        }
    }
}