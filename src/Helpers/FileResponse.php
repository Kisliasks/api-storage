<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTO\File;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileResponse
{
    private const CONTENT_TYPE_DOWNLOAD = 'image/gif';

    public static function downloadFileResponse(File $file): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file->getLocalPath());
        $response->headers->set('Content-Type', self::CONTENT_TYPE_DOWNLOAD);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getPayload()['file_name']
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

    public static function httpAcceptedResponse(array $responseData = null): JsonResponse
    {
        return new JsonResponse(
            $responseData,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public static function errorFileResponse(
        mixed $responseData = null,
        int $responseCode = null,
    ): JsonResponse {
        return new JsonResponse(
            ['Error' => $responseData],
            $responseCode ?? JsonResponse::HTTP_BAD_REQUEST,
        );
    }
}