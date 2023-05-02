<?php

namespace App\FileModule\Controller;

use App\Application\DTO\File;
use App\Application\Helpers\FileHelper;
use App\Application\Helpers\FileResponse;
use App\Application\Helpers\FileExtractor;
use App\FileModule\Presenter\FilePresenter;
use Symfony\Component\Validator\Validation;
use App\FileModule\ValueObject\TempMovedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Application\Error\ValidateErrorGenerator;
use App\FileModule\Factory\FileConstraintFactory;
use App\Application\Error\AbstractProblemJsonError;
use App\Application\Interfaces\FileServiceInterface;
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
    ) {
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
                $tempMovedFile->delete();

                return FileResponse::errorFileResponse(
                    $e->jsonSerialize(),
                    $e->getCode(),
                );
            } 
        }
        $approvedFile = $this->fileHelper->processApprovedFile($tempMovedFile);
        
        $fileToDb = new File(
            $approvedFile->getFileUuid(),
            '378509845',
            FileExtractor::extract(
                $this->fileHelper->generateFilePayload(
                    $requestData['file_name'],
                    $approvedFile,
                )
            ),
            $this->fileHelper->generateDownloadLink(
                $requestData['file_name'],
                $approvedFile->getFileUuid(),
            ),
        );
 
        return FileResponse::httpOkResponse(
            $this->filePresenter->presentToHttp(
                $this->fileService->createFile($fileToDb)
            )
        );
    }
}