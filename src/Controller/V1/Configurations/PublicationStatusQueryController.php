<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationStatusQueryController extends AbstractController
{
    #[Route('/v1/configurations/publication/status/query', name: 'app_v1_configurations_publication_status_query')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationStatusQueryController.php',
        ]);
    }
}
