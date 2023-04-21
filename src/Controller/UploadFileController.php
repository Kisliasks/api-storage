<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFileController extends AbstractController
{
    public function uploadFile(Request $request): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('image');
        
        $fileName = uniqid('someFile');
        $fileName .= "." . $file->getClientOriginalExtension();

        $file->move('../files_storage', $fileName);
 
        return new Response('good');
    }
}