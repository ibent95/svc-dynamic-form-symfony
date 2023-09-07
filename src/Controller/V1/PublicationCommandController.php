<?php

namespace App\Controller\V1;

use App\Entity\PublicationFormVersion;
use App\Entity\PublicationType;
use App\Service\CommonService;
use App\Service\DynamicFormService;

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
    private $responseData;
    private $responseStatusCode;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $dynamicFormSvc;

    public function __construct(LoggerInterface $logger, CommonService $commonSvc, DynamicFormService $dynamicFormSvc)
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

        $this->commonSvc            = $commonSvc;
        $this->dynamicFormSvc       = $dynamicFormSvc;
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


        try {
            $entityManager->getConnection()->beginTransaction();

            // Create command
            if (!$uuid) {

                $publicationType            = $entityManager->getRepository(PublicationType::class)->findOneBy([
                    'publication_type_code' => $request->request->get('publication_type_code')
                ]);

                $publicationFormVersion     = $entityManager->getRepository(PublicationFormVersion::class)->findOneBy([
                    'publication_type_id' => $publicationType->getId(),
                    'flag_active' => TRUE
                ]);

                $publicationFormConfigs     = $publicationFormVersion->getPublicationForms();

                //$this->responseData['data']['form_data']    = $request->request->all();
                //$this->responseData['data']['form_configs'] = $publicationFormConfigs;
                $dynamicFormData            = $this->dynamicFormSvc->getDataByDynamicForm($request, $this->commonSvc->normalizeObject($publicationFormConfigs));

                $this->logger->info('Create', $dynamicFormData->toArray());

            }

            // Update command
            if ($uuid) {
                $this->logger->info('Update');
            }

            $entityManager->getConnection()->commit();

            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success on save publication data!';
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