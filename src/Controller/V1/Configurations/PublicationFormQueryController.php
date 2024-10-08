<?php

namespace App\Controller\V1\Configurations;

use App\Entity\PublicationForm;
use App\Service\CommonService;
use App\Service\DynamicFormService;
use App\Service\PublicationFormService;
use App\Service\PublicationService;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PublicationFormQueryController extends AbstractController
{
    private $logger;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private DynamicFormService $dynamicFormSvc;
    private PublicationFormService $publicationFormSvc;
    private Collection $response;
    private array $responseData;
    private int $responseStatusCode;

    public function __construct(
        LoggerInterface $logger,
        CommonService $commonSvc,
        DynamicFormService $dynamicFormSvc,
        PublicationFormService $publicationFormSvc
    )
    {
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
        $this->publicationFormSvc   = $publicationFormSvc;


        // Response initial value
        $this->responseData         = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode   = 400;
        $this->response             = $this->commonSvc->setResponse($this->responseData, $this->responseStatusCode);

    }

    #[Route('/api/v1/configurations/publication-form', name: 'app_v1_configurations_publication_form_index')]
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

    #[Route('/api/v1/configurations/publication-forms', methods: ['GET'], name: 'app_v1_configurations_publication_form_all')]
    public function all(Request $request): JsonResponse
    {

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_configurations_publication_form_get_all.',
        ], 500);

        try {
            $params                     = [
                'search_key' => $request->get('search_key'),
                'uuid_publication_form_version' => $request->get('uuid_publication_form_version')
            ]; // 'flag_active' => true
            $orderBy                    = [
                'updated_at' => 'DESC',
                'order_position' => 'ASC'
            ];
            $paginator                  = $this->commonSvc->setPaginator($request);

            $publicationFormData        = $this->publicationFormSvc->getQueryBuilderAll(
                $params,
                $orderBy,
                $paginator->get('limit'),
                $paginator->get('offset')
            );
            $publicationFormsTotalCount     = $publicationFormData['count'];
            $publicationFormsData           = $this->commonSvc->normalizeObject($publicationFormData['data'], ['internal'], ['grid_system']) ;

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication forms configuration data!',
                'data'     => $publicationFormsData,
                'count'    => $publicationFormsTotalCount,
            ], 200);

            $this->logger->info('Get publication fo rms configuration data: ');
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

    #[Route('/api/v1/configurations/publication-forms/{uuid}', methods: ['GET'], name: 'app_v1_configurations_publication_form_detail')]
    public function detail(Request $request, string $uuid): JsonResponse
    {
        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_configurations_publication_form_get_all.',
        ], 500);

        try {
            $params              = ['uuid' => $uuid]; // 'flag_active' => true

            $publicationFormDataRaw              = $this->publicationFormSvc->findOneBy($params);
            $publicationFormData                 = $this->commonSvc->normalizeObject($publicationFormDataRaw, ['internal'], [], null, true);

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication forms configuration detail data!',
                'data'     => $publicationFormData,
            ], 200);

            $this->logger->info('Get publication forms configuration detail data: ');
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publication forms configuration detail data!'
            ], 400);
            $this->logger->error(
                'Get publication forms configuration detail data exception log: ' . $e->getMessage()
                . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

}
