<?php

namespace App\Controller\V1;

use App\Entity\Publication;
use App\Entity\PublicationFormVersion;
use App\Entity\PublicationType;
use App\Service\CommonService;
use App\Service\DynamicFormService;
use App\Service\PublicationService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationCommandController extends AbstractController
{
    private $logger;
    private $loggerMessage;
    private $responseData;
    private $responseStatusCode;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $dynamicFormSvc;
    private $publicationSvc;

    public function __construct(LoggerInterface $logger, CommonService $commonSvc, DynamicFormService $dynamicFormSvc, PublicationService $publicationSvc)
    {
        $this->logger               = $logger;
        $this->loggerMessage        = 'No process is running.';

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

        $this->commonSvc            = $commonSvc;
        $this->dynamicFormSvc       = $dynamicFormSvc;
        $this->publicationSvc       = $publicationSvc;
    }

    #[Route('/api/v1/publications', methods: ['POST'], name: 'app_v1_publication_command_post')]
    #[Route('/api/v1/publications/{uuid}', methods: ['PUT'], name: 'app_v1_publication_command_put')]
    public function save(ManagerRegistry $doctrine, Request $request, String $uuid = NULL): JsonResponse
    {
        /** @var $entityManager EntityManager */
        $entityManager = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 200;
        $this->loggerMessage            = 'Save Publication data is running.';

        try {
            $entityManager->getConnection()->beginTransaction();

            $publication                = ($uuid) ? $entityManager->getRepository(Publication::class)->findOneBy([
                'uuid' => $uuid
            ]) : null;
            $publicationType            = $entityManager->getRepository(PublicationType::class)->findOneBy([
                'publication_type_code' => $request->request->get('publication_type_code')
            ]);
            $publicationFormVersion     = $entityManager->getRepository(PublicationFormVersion::class)->findOneBy([
                'id_publication_type' => $publicationType->getId(),
                'flag_active' => TRUE
            ]);

            $publicationData                = $this->publicationSvc->getDataByDynamicForm($request, $publicationFormVersion, $publication);

            // Create command
            if (!$uuid) {
                $entityManager->persist($publicationData);
                $this->loggerMessage = 'Create publication data: ';
            }

            // Update command
            if ($uuid) {
                $this->loggerMessage = 'Update publication data: ';
            }
            
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success on save publication data!';
            $this->logger->info($this->loggerMessage, $this->commonSvc->normalizeObject($publicationData));
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();

            $this->responseData['message']  = 'Error on save publication data!';
            $this->responseStatusCode       = 400;
            $this->logger->error('Insert publication data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), $e->getTraceAsString()]);
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    //#[Route('/api/v1/publication-test', methods: ['POST'], name: 'app_v1_publication_insert')]
    //public function insert(ManagerRegistry $doctrine): JsonResponse
    //{
    //    $entityManager = $doctrine->getManager();

    //    $this->responseData['info']     = 'error';
    //    $this->responseData['message']  = '';
    //    $this->responseStatusCode       = 500;

    //    $publication = $entityManager->getRepository(Publication::class);

    //    try {
    //        $doctrine->connection->beginTransaction();

    //        $this->responseData['data'] = $publication->findAll();

    //        $doctrine->connection->commit();
    //    } catch (\Exception $e) {
    //        $doctrine->connection->rollBack();

    //        $this->responseData['message']  = 'Error on get publication form metadata!';
    //        $this->responseStatusCode       = 400;
    //        $this->logger->error('Insert publication data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(), [$e->getFile(), $e->getTraceAsString()]);
    //    }

    //    return $this->json($this->responseData, $this->responseStatusCode);
    //}

}