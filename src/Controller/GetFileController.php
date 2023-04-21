<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetFileController extends AbstractController
{
    public function getFile(Request $request): Response
    {
        $response = "Good! Api application its working!";
        
        if ($request->query->get('id') < 3) {
            $response = 'Bad request';
        } 
 
        return new Response($response);
    }
}