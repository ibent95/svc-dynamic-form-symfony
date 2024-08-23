<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationGeneralTypeQueryController extends AbstractController
{
    #[Route('/v1/configurations/publication/general/type/query', name: 'app_v1_configurations_publication_general_type_query')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationGeneralTypeQueryController.php',
        ]);
    }
}
