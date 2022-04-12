<?php

namespace App\Controller\V1;

use App\Entity\PublicationFormVersion;
use App\Entity\Publication;
use App\Entity\PublicationGeneralType;
use App\Entity\PublicationStatus;
use App\Entity\PublicationType;
use App\Repository\PublicationFormVersionRepository;
use App\Repository\PublicationRepository;
use App\Service\Common;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\Request;
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

class PublicationQueryController extends AbstractController
{
    private $logger;
    private $responseData;
    private $responseStatusCode;
    private $request;
    private $exprBuilder;
    private $criteria;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger               = $logger;

        $this->request              = Request::createFromGlobals();

        $this->exprBuilder          = Criteria::expr();
        $this->criteria             = new Criteria();

        // Response initial value
        $this->responseData         = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode   = 400;
    }

    /** ================================ Required functions for publication ================================ */

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

    #[Route('/api/v1/publications', methods: ['GET'], name: 'app_v1_publications')]
    public function getAll(ManagerRegistry $doctrine, Common $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        $result                         = NULL;

        try {
            // PublicationType
            $publicationsParams         = []; // 'publication_type_code' => $publicationTypeCode
            $publicationsRaw            = new ArrayCollection($entityManager->getRepository(Publication::class)->findBy($publicationsParams));

            $index = 0;
            $publications               = ($publicationsRaw) ? $publicationsRaw->map(function ($item) use ($common, &$index) {
                $resultItem                             = $common->normalizeObject($item);

                $resultItem['no']                       = $index + 1;
                $resultItem['metadata']                 = $common->normalizeObject($item->getPublicationMeta());
                $resultItem['publication_general_type'] = $common->normalizeObject($item->getPublicationGeneralType());
                $resultItem['publication_type']         = $common->normalizeObject($item->getPublicationType());
                $resultItem['publication_status']       = $common->normalizeObject($item->getPublicationStatus());

                $index++;
                return $resultItem;
            })->toArray() : NULL;

            // Response data
            $this->responseData['data']     = $publications;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication form metadata!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get publication form: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            //$this->responseData['data']     = $e;
            $this->responseData['message']  = 'Error on get publication data!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), 'trace => ', $e->getTrace()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/publication', methods: ['POST'], name: 'app_v1_publication_insert')]
    public function insert(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        $publication = $entityManager->getRepository(Publication::class);

        try {
            $doctrine->connection->beginTransaction();

            $this->responseData['data'] = $publication->findAll();

            $doctrine->connection->commit();
        } catch (\Exception $e) {
            $doctrine->connection->rollBack();
            $this->responseData['message']  = 'Error on get publication form metadata!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Insert publication data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), $e->getTraceAsString()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    /** =============================== Form metadata (Forms of Publication) =============================== */

    #[Route('/api/v1/publication/form-metadata/{publicationTypeCode}', methods: ['GET'], name: 'app_v1_publication_form_metadata')]
    public function getFormMetadata(ManagerRegistry $doctrine, Common $common, String $publicationTypeCode): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();
        //$this->serializer               = $serializer;

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        $formVersionCode                = $this->request->query->get('form-version-code');

        try {
            // PublicationType
            $publicationTypeParams          = ['publication_type_code' => $publicationTypeCode];
            $publicationType                = $entityManager->getRepository(PublicationType::class)->findOneBy($publicationTypeParams);

            // FormVersion
            $formVersionRaw                 = $publicationType->getFormVersion();
            $this->criteria->where($this->exprBuilder->eq('flag_active', true));
            if ($formVersionCode) $this->criteria->orWhere($this->exprBuilder->eq('id', $publicationType->getId()));
            $formVersionMatchFirst          = $formVersionRaw->matching($this->criteria)->first();
            $formVersion                    = ($formVersionMatchFirst) ? $common->normalizeObject($formVersionMatchFirst) : NULL;

            // Set fields in recursive pattern
            $formsNormalize                 = $common->normalizeObject($formVersionMatchFirst->getForm());
            $forms                          = $common->setFields($formsNormalize);

            // Forms of FormVersion
            if ($formVersion) $formVersion['forms']           = $forms;

            // Response data
            $this->responseData['data']     = $formVersion ?: [];
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication form metadata!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get publication form: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get publication form metadata!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), $e->getTraceAsString()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode); // new JsonResponse($this->responseData, $this->responseStatusCode, ["Content-Type" => "application/json"])
    }

    /** ======================================== Template functions ======================================== */

    public function template(ManagerRegistry $doctrine)
    {
        $entityManager              = $doctrine->getManager();

        $publications               = $entityManager->getRepository(Publication::class)->findAll();
        $publicationGeneralType     = $entityManager->getRepository(PublicationGeneralType::class)->findOneBy(['id' => 1]);
        $publicationType            = $entityManager->getRepository(PublicationType::class)->findOneBy(['id' => 1]);
        $publicationStatus          = $entityManager->getRepository(PublicationStatus::class)->findOneBy(['id' => 1]);

        $this->responseData['data'] = $publications;
    }

}