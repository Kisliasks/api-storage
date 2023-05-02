<?php

use App\FileModule\Controller\DownloadFileController;
use App\FileModule\Controller\GetFileController;
use App\FileModule\Controller\UploadFileController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('get_file', 'api/files/get/{id}')->controller([GetFileController::class, 'getFile'])->methods(['GET'])->format('json');
    $routes->add('upload_file', 'api/files/upload')->controller([UploadFileController::class, 'uploadFile'])->methods(['POST'])->format('json');
    $routes->add('download_file', 'api/files/download/{token}')->controller([DownloadFileController::class, 'downloadFile'])->methods(['GET']);
};
