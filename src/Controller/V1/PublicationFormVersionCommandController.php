<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormVersionCommandController extends AbstractController
{
    #[Route('/v1/publication-form-versions', methods: ['POST'], name: 'app_v1_publication_form_version_command')]
    public function insert(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/V1/PublicationFormVersionCommandController.php',
        ]);
    }
}
