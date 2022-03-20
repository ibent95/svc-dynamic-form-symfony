<?php

namespace App\Controller\V1;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
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

    #[Route('/api/v1/research', name: 'app_v1_research')]
    public function index(): JsonResponse
    {
        $this->logger->info('The root route has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the research menu!';
        $this->responseData['data'] = [
            'message' => 'Welcome to research!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }
}
