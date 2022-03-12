<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationController extends AbstractController
{
    #[Route('/api/v1/publication', name: 'app_v1_publication')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ]);
    }

    public function getFormMetadata(String $formCode, String $formVersionCode = null): JsonResponse
    {

        try {
            //code...
        } catch (\Exception $e) {

        }

        return $this->json([
            'data' => ''
        ]);
    }
}
