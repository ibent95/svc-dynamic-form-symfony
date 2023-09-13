<?php

namespace App\Controller\V1\Master;

use App\Entity\PublicationGeneralType;
use App\Service\CommonService;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationGeneralTypeQueryController extends AbstractController
{
    private $logger;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $responseData;
    private $responseStatusCode;

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

    /** ============================= Required functions for publication general type ============================== */

    #[Route('/api/v1/publication-general-types', methods: ['GET'], name: 'app_v1_publication_general_types')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication general type menu has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the publication general types API!';
        $this->responseData['data'] = [
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/publication-general-types', methods: ['GET'], name: 'app_v1_publication_general_types')]
    public function getAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            $params                         = [];
            $publicationTypes               = $entityManager->getRepository(PublicationGeneralType::class)
                ->findBy($params);

            // Response data
            $this->responseData['data']     = $publicationTypes;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication general types data!';
            $this->responseStatusCode       = 200;

            $this->logger->info(
                'Get publication general types data: ' . json_encode($this->responseData['data'])
            );
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get publication general types data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get publication general types data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route(
        '/api/v1/master/publication-general-types',
        methods: ['GET'],
        name: 'app_v1_master_publication_general_types'
    )]
    public function getMasterDataAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // PublicationGeneralType
            $publicationTypesParams          = [
                'flag_active' => true
            ];
            $publicationTypes                = $entityManager->getRepository(PublicationGeneralType::class)
                ->findBy($publicationTypesParams);

            // Response data
            $this->responseData['data']     = $publicationTypes;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get master data of publication general types!';
            $this->responseStatusCode       = 200;

            $this->logger->info(
                'Get master data of publication general types: ' . json_encode($this->responseData['data'])
            );
        } catch (\Exception $e) {
            $this->responseData['message']  = 'Error on get master data of publication general types!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Get master data of publication general types exception log: '
                . $e->getMessage() . ', line: ' . $e->getLine()
                ,
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}