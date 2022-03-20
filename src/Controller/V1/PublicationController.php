<?php

namespace App\Controller\V1;

use App\Entity\PublicationFormVersion;
use App\Entity\Publication;
use App\Entity\PublicationGeneralType;
use App\Entity\PublicationStatus;
use App\Entity\PublicationType;
use App\Repository\PublicationFormVersionRepository;
use App\Repository\PublicationRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class PublicationController extends AbstractController
{
    private $logger;
    private $responseData;
    private $responseStatusCode;
    private $doctrine;
    private $connection;
    private $entityManager;
    private $encoders;
    private $normalizers;
    private $serializer;
    private $datetimeSerializer;

    public function __construct(LoggerInterface $logger, ManagerRegistry $doctrine)
    {
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

        $this->serializer = new Serializer([new ObjectNormalizer(), new DateTimeNormalizer()], [new JsonEncoder()]);
        //$this->datetimeSerializer = $datetimeSerializer;
    }

    #[Route('/api/v1/publication', methods: ['GET'], name: 'app_v1_publication')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication menu has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the publication menu!';
        $this->responseData['data'] = [
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/publication/form-metadata', methods: ['GET'], name: 'app_v1_publication_form_metadata')]
    public function getFormMetadata(String $formCode = null, String $formVersionCode = null): JsonResponse
    {
        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 400;

        $result = NULL;

        try {
            //$this->connection->beginTransaction();
            $publications           = $this->entityManager->getRepository(Publication::class)->findAll();
            $publicationGeneralType = $this->entityManager->getRepository(PublicationGeneralType::class)->findOneBy(['id' => 1]);
            $publicationType        = $this->entityManager->getRepository(PublicationType::class)->findOneBy(['id' => 1]);
            $publicationStatus      = $this->entityManager->getRepository(PublicationStatus::class)->findOneBy(['id' => 1]);
            $formVersion            = $this->entityManager->getRepository(PublicationFormVersion::class)->findOneBy(['id' => 1]);

            $this->responseData['data']     = [
                'publications'              => $publications,
                'publication_general_type'  => $publicationGeneralType,
                'publication_type'          => $publicationType,
                'publication_status'        => $publicationStatus,
                'form_metadata'             => $formVersion,
            ];
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication form metadata!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get publication form!', $this->responseData['data']);

            //$this->connection->commit();
        } catch (\Exception $e) {
            //$this->connection->rollBack();
            $this->responseData['message']  = 'Error on get publication form metadata!';
            $this->responseStatusCode       = 500;
            $this->logger->error('Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine());
        }

        return $this->json($this->responseData, $this->responseStatusCode); // new JsonResponse($this->responseData, $this->responseStatusCode, ["Content-Type" => "application/json"])
    }

    #[Route('/api/v1/publication', methods: ['POST'], name: 'app_v1_publication_insert')]
    public function insertBy(): JsonResponse
    {
        $this->responseData['info'] = 'success';
        $this->responseData['message'] = '';
        $this->responseStatusCode = 200;

        $publication = $this->entityManager->getRepository(Publication::class);

        try {
            $this->connection->beginTransaction();

            $this->responseData['data'] = $publication->findAll();

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            $this->logger->error('Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), $e->getTrace());
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}