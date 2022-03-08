<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicationController extends AbstractController
{
    #[Route('/api/v1/publication', name: 'app_v1_publication')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ]);
    }
}
