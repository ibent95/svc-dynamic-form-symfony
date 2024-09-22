<?php

namespace App\Controller\V1\Configurations;

use App\Service\CommonService;
use App\Service\DynamicFormService;
use App\Service\PublicationFormVersionService;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormVersionQueryController extends AbstractController
{
    private $logger;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private DynamicFormService $dynamicFormSvc;
    private PublicationFormVersionService $publicationFormVersionSvc;
    private Collection $response;
    private array $responseData;
    private int $responseStatusCode;

    public function __construct(
        LoggerInterface $logger,
        CommonService $commonSvc,
        DynamicFormService $dynamicFormSvc,
        PublicationFormVersionService $publicationFormVersionSvc
    ) {
        $this->logger               = $logger;

        $this->request              = Request::createFromGlobals();

        $this->exprBuilder          = Criteria::expr();
        $this->criteria             = new Criteria();

        $formVersion['grid_system']['bootstrap']    = [
            'type' => 'bootstrap',
            'cols' => 12,
            'config' => [
                'text_1' => [
                    'colspan' => 3,
                    'rowspan' => 2,
                ],
                'select_1' => [
                    'colspan' => 3,
                    'rowspan' => 2,
                ],
                'try_stepper_1' => [
                    'colspan' => 6,
                    'rowspan' => 6,
                ],
                'try_stepper_1_step_1' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'date_2' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
            ],
        ];

        $formVersion['grid_system']['tailwind']     = [
            'type' => 'tailwind',
            'cols' => 12,
            'config' => [
                'text_1' => [
                    'colspan' => 6,
                ],
                'select_1' => [
                    'colspan' => 6,
                ],
                'try_stepper_1' => [
                    'colspan' => 6,
                    'rowspan' => 6,
                ],
                'try_stepper_1_step_1' => [
                    'colspan' => 12,
                ],
                'date_2' => [
                    'colspan' => 6,
                ],
                'month_2' => [
                    'colspan' => 6,
                ],
                'year_2' => [
                    'colspan' => 6,
                ],
                'time_1' => [
                    'colspan' => 6,
                ],
                'datetime_1' => [
                    'colspan' => 6,
                ],
                'daterange_1' => [
                    'colspan' => 6,
                ],
                'timerange_1' => [
                    'colspan' => 6,
                ],
                'datetimerange_1' => [
                    'colspan' => 6,
                ],
                'try_stepper_1_step_2' => [
                    'colspan' => 12,
                ],
            ],
        ];

        $field_configs              = [
            'orientation'   => 'horizontal',
            'linear'        => true,
        ];

        $validation_config          = [
            'pattern'       => 'horizontal',
            'error_message' => true,
        ];

        // Services
        $this->commonSvc            = $commonSvc;
        $this->dynamicFormSvc       = $dynamicFormSvc;
        $this->publicationFormVersionSvc   = $publicationFormVersionSvc;


        // Response initial value
        $this->responseData         = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode   = 400;
        $this->response             = $this->commonSvc->setResponse($this->responseData, $this->responseStatusCode);
    }

    #[Route('/api/v1/configurations/publication-form-version', name: 'app_v1_configurations_publication_form_version_query')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication forms configuration menu has been accessed!');

        $this->response = $this->commonSvc->setResponse([
            'info' => 'success',
            'message' => 'Success to access the publication forms configuration API!',
            'data' => [
                'message'     => 'Welcome to publication forms configuration API!',
                'date'         => date('Y-m-d'),
            ],
        ], 200);

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    #[Route('/api/v1/configurations/publication-form-versions', methods: ['GET'], name: 'app_v1_configurations_publication_form_version_get_all')]
    public function all(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        /** @var ObjectManager $entityManager */
        $entityManager                  = $doctrine->getManager();

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_configurations_publication_form_version_get_all.',
        ], 500);

        try {
            $params                     = []; // 'flag_active' => true
            $orderBy                    = ['updated_at' => 'DESC'];
            $paginator                  = $this->commonSvc->setPaginator($request);

            $publicationFormData            = $this->publicationFormVersionSvc->getQueryBuilderAll($params, $orderBy, $paginator->get('limit'), $paginator->get('offset'));
            $publicationFormsTotalCount     = $publicationFormData['count'];
            $publicationFormsData           = $publicationFormData['data'];

            //$publicationFormsEntity         = $entityManager->getRepository(PublicationForm::class);
            //$publicationFormsTotalCount     = $publicationFormsEntity->count($params);
            //$publicationFormsData           = $publicationFormsEntity->findBy(
            //    $params, $orderBy, $paginator->get('limit'), $paginator->get('offset')
            //);

            //$data = $this->commonSvc->makeHidden(['id'], $publicationFormsData);

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication forms configuration data!',
                'data'     => $publicationFormsData,
                'count'    => $publicationFormsTotalCount,
            ], 200);

            $this->logger->info('Get publication forms configuration data: ');
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publication forms configuration data!'
            ], 400);
            $this->logger->error(
                'Get publication forms configuration data exception log: ' . $e->getMessage()
                    . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

}
