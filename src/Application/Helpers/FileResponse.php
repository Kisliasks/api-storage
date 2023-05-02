<?php

declare(strict_types=1);

namespace App\Application\Helpers;

use App\FileModule\ValueObject\FileFromStorage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileResponse
{

    public static function downloadFileResponse(FileFromStorage $file): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file->getFilePath());
        $contentType = FileHelper::generateFileContentType($file);

        $response->headers->set('Content-Type', $contentType);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getFileName(),
        );

        return $response;
    }

    public static function httpNotFoundResponse(): JsonResponse
    {
        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    public static function httpOkResponse(array $responseData = null): JsonResponse
    {
        return new JsonResponse(
            $responseData,
            JsonResponse::HTTP_OK,
        );
    }

    public static function errorFileResponse(
        mixed $responseData = null,
        int $responseCode = null,
    ): JsonResponse {
        return new JsonResponse(
            ['Error' => $responseData],
            match ($responseCode) {
                0, null => JsonResponse::HTTP_BAD_REQUEST,
                $responseCode > 0 => $responseCode,
            }
        );
    }
}