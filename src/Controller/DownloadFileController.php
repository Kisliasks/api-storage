<?php

namespace App\Controller;

use App\Helpers\FileHelper;
use App\Helpers\FileResponse;
use App\Presenter\FilePresenter;
use App\ValueObject\ParsedFileToken;
use App\Interfaces\FileServiceInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DownloadFileController extends AbstractController
{
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly FilePresenter $filePresenter,
        private readonly FileHelper $fileHelper,
    ) {

    }

    public function downloadFile(Request $request): Response
    {
        $fileToken = $request->attributes->get('token');

        try {
            /** @var ParsedFileToken $parsedFileToken */
            $parsedFileToken = $this->fileHelper->parseFileTokenFromRequest($fileToken);

            return FileResponse::downloadFileResponse(
                $this->fileService->getFileByToken($parsedFileToken)
            );
        } catch (EntityNotFoundException) {
            return FileResponse::httpNotFoundResponse();
        } catch (Exception $e) {
            return FileResponse::errorFileResponse($e->getMessage());
        }
    }
}