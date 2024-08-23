<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationGeneralTypeCommandController extends AbstractController
{
    #[Route('/v1/configurations/publication/general/type/command', name: 'app_v1_configurations_publication_general_type_command')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationGeneralTypeCommandController.php',
        ]);
    }
}
