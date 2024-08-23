<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationTypeQueryController extends AbstractController
{
    #[Route('/v1/configurations/publication/type/query', name: 'app_v1_configurations_publication_type_query')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationTypeQueryController.php',
        ]);
    }
}
