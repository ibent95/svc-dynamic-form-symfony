<?php

namespace App\Controller\V1\Configurations;

use App\Service\CommonService;
use App\Service\DynamicFormService;
use App\Service\PublicationFormService;
use App\Service\PublicationFormVersionService;
use App\Service\PublicationService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormCommandController extends AbstractController
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
    private $publicationFormSvc;
    private $publicationFormVersionSvc;

    public function __construct(
        LoggerInterface $logger,
        CommonService $commonSvc,
        DynamicFormService $dynamicFormSvc,
        PublicationFormService $publicationFormSvc,
        PublicationFormVersionService $publicationFormVersionSvc,
    ) {
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

        $this->commonSvc                 = $commonSvc;
        $this->dynamicFormSvc            = $dynamicFormSvc;
        $this->publicationFormSvc        = $publicationFormSvc;
        $this->publicationFormVersionSvc = $publicationFormVersionSvc;
    }

    #[Route('/api/v1/configurations/publication-forms', methods: ['POST'], name: 'app_v1_configurations_publication_form_post')]
    #[Route('/api/v1/configurations/publication-forms', methods: ['PUT'], name: 'app_v1_configurations_publication_form_put')]
    public function save(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        /** @var ObjectManager $entityManager */
        $entityManager = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 200;
        $this->loggerMessage            = 'Save Publication Form field data is running.';

        $requestAll                     = $request->request->all();
        $uuid                           = ($request->get('uuid')) ? $requestAll['uuid'] : null ;

        try {
            $entityManager->getConnection()->beginTransaction();

            $publicationFormData        = $this->publicationFormSvc->save($request);

            // Create command
            if (!$uuid) {
                $entityManager->persist($publicationFormData);
                $this->loggerMessage    = 'Create configuration of publication form data: ';
            }

            // Update command
            if ($uuid) {
                $this->loggerMessage    = 'Update configuration of publication form data: ';
            }

            $entityManager->flush();
            $entityManager->getConnection()->commit();

            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success on save configuration of publication form data!';
            $this->logger->info($this->loggerMessage, $this->commonSvc->normalizeObject($publicationFormData, ['internal']));
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();

            $this->responseData['info']     = 'error';
            $this->responseData['message']  = 'Error on save configuration of publication form data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Save configuration of publication form data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), $e->getTraceAsString(), $request->request->all()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/configurations/publication-forms/{uuid}/disable', methods: ['POST'], name: 'app_v1_configurations_publication_form_disable')]
    public function disable(ManagerRegistry $doctrine, Request $request, string $uuid): JsonResponse
    {
        /** @var ObjectManager $entityManager */
        $entityManager = $doctrine->getManager();

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 200;
        $this->loggerMessage            = 'Save Publication Form field data is running.';

        $requestAll                     = $request->request->all();

        try {
            $entityManager->getConnection()->beginTransaction();

            $publicationFormData        = $this->publicationFormSvc->disable($request, $uuid);

            $entityManager->flush();
            $entityManager->getConnection()->commit();

            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success on disable configuration of publication form data!';
            $this->logger->info($this->loggerMessage, $this->commonSvc->normalizeObject($publicationFormData, ['internal']));
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();

            $this->responseData['info']     = 'error';
            $this->responseData['message']  = 'Error on disable configuration of publication form data!';
            $this->responseStatusCode       = 400;
            $this->logger->error(
                'Disable configuration of publication form data exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), $e->getTraceAsString(), $request->request->all()]
            );
        }

        return $this->json($this->responseData, $this->responseStatusCode);
    }

}
