<?php

namespace App\Controller\V1\Configurations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormVersionCommandController extends AbstractController
{
    #[Route('/api/v1/configurations/publication-form-versions', methods: ['POST'], name: 'app_v1_configurations_publication_form_version_post')]
    #[Route('/api/v1/configurations/publication-form-versions', methods: ['PUT'], name: 'app_v1_configurations_publication_form_version_put')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/Configurations/PublicationFormVersionCommandController.php',
        ]);
    }
}
