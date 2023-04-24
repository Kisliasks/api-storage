<?php

use App\Controller\GetFileController;
use App\Controller\UploadFileController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('get_file', '/files/get/{id}')->controller([GetFileController::class, 'getFile'])->methods(['GET']);
    $routes->add('upload_file', '/files/upload')->controller([UploadFileController::class, 'uploadFile'])->methods(['POST'])->format('json');
};
