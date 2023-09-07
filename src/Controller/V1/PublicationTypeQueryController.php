<?php

namespace App\Controller\V1;

use App\Entity\PublicationType;
use App\Service\CommonService;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationTypeQueryController extends AbstractController
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

    /** ================================ Required functions for publication ================================ */

    #[Route('/api/v1/publication-type', methods: ['GET'], name: 'app_v1_publication_type')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication type menu has been accessed!');

        $this->responseData['info'] = 'success';
        $this->responseData['message'] = 'Success to access the publication menu!';
        $this->responseData['data'] = [
            'message' => 'Welcome to publication!',
            'date' => date('Y-m-d'),
        ];

        $this->responseStatusCode = 200;

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/publication-types', methods: ['GET'], name: 'app_v1_publication_types')]
    public function getAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // PublicationType
            $publicationTypesParams          = [];
            $publicationTypes                = $entityManager->getRepository(PublicationType::class)->findBy($publicationTypesParams);

            // Response data
            $this->responseData['data']     = $publicationTypes;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication type data!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get publication type data: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            //$this->responseData['data']     = $e;
            $this->responseData['message']  = 'Error on get publication type data!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Get publication type data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), 'trace => ', $e->getTrace()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/master/publication-type', methods: ['GET'], name: 'app_v1_master_publication_type')]
    public function getMasterDataAll(ManagerRegistry $doctrine, CommonService $common): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        try {
            // PublicationType
            $publicationTypesParams          = [];
            $publicationTypes                = $entityManager->getRepository(PublicationType::class)->findBy($publicationTypesParams);

            // Response data
            $this->responseData['data']     = $publicationTypes;
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get master data of publication type!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get master data of publication type: ' . json_encode($this->responseData['data']));
        } catch (\Exception $e) {
            //$this->responseData['data']     = $e;
            $this->responseData['message']  = 'Error on get master data of publication type!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Get master data of publication type exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), 'trace => ', $e->getTrace()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}