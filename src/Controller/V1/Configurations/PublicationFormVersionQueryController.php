<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormVersionQueryController extends AbstractController
{
    #[Route('/v1/configurations/publication/form/version/query', name: 'app_v1_configurations_publication_form_version_query')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationFormVersionQueryController.php',
        ]);
    }
}
