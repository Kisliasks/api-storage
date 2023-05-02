<?php

namespace App\FileModule\Controller;

use Exception;
use App\Application\Helpers\FileHelper;
use App\Application\Helpers\FileResponse;
use Doctrine\ORM\EntityNotFoundException;
use App\FileModule\Presenter\FilePresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FileModule\ValueObject\ParsedFileToken;
use App\Application\Interfaces\FileServiceInterface;
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
                $this->fileService->getFileFromStorage($parsedFileToken)
            );
        } catch (EntityNotFoundException) {
            return FileResponse::httpNotFoundResponse();
        } catch (Exception $e) {
            return FileResponse::errorFileResponse(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}