<?php

namespace App\Controller\V1;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainQueryController extends AbstractController
{
    private $logger;
    private $responseData;
    private $responseStatusCode;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->responseData = [
            'info' => '',
            'message' => '',
            'data' => [],
        ];
        $this->responseStatusCode = 400;
    }

    #[Route('/api/v1', name: 'app_v1_main')]
    public function index(): JsonResponse
    {
        $this->logger->info('The root route has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Welcome to Dynamic Form service with Symfony 6.';
        $this->responseData['data'] = [
            'message' => 'Welcome to Dynamic Form service with Symfony 6.',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }
}
