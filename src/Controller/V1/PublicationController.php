<?php

namespace App\Controller\V1;

use App\Entity\Publication;

use App\Repository\PublicationRepository;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationController extends AbstractController
{
    private $logger;
    private $responseData;
    private $responseStatusCode;
    private $doctrine;
    private $connection;
    private $entityManager;

    public function __construct(LoggerInterface $logger, ManagerRegistry $doctrine) {
        $this->logger = $logger;
        $this->responseData = [
            'info' => '',
            'message' => '',
            'data' => [],
        ];
        $this->responseStatusCode = 400;
        $this->doctrine = $doctrine;
        $this->connection = $doctrine->getConnection();
        $this->entityManager = $this->doctrine->getManager();
    }

    #[Route('/api/v1/publication', name: 'app_v1_publication')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ]);
    }

    public function getFormMetadata(String $formCode, String $formVersionCode = null): JsonResponse
    {
        $this->responseData['info'] = 'success';
        $this->responseData['message'] = '';
        $this->responseStatusCode = 200;

        $publication = $this->entityManager->getRepository(Publication::class);

        try {
            //$this->connection->beginTransaction();



            //$this->connection->commit();
        } catch (\Exception $e) {
            //$this->connection->rollBack();
            $this->logger->error('Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), $e->getTrace());
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }
}
