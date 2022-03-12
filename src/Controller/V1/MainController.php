<?php

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/api/v1', name: 'app_v1_main')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to Dynamic Form service with Symfony 6',
            'date' => date('Y-m-d'),
        ]);
    }
}
