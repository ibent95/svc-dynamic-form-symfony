<?php

namespace App\Controller\V1;

use App\Entity\Publication;
use App\Entity\PublicationForm;
use App\Entity\PublicationGeneralType;
use App\Entity\PublicationMeta;
use App\Entity\PublicationStatus;
use App\Entity\PublicationType;
use App\Service\CommonService;
use App\Service\DynamicFormService;
use App\Service\PublicationService;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublicationQueryController extends AbstractController
{
    private $logger;
    private $request;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $dynamicFormSvc;
    private $publicationSvc;
    private Collection $response;
    private array $responseData;
    private int $responseStatusCode;

    public function __construct(
        LoggerInterface $logger,
        CommonService $commonSvc,
        DynamicFormService $dynamicFormSvc,
        PublicationService $publicationSvc
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
        $this->publicationSvc       = $publicationSvc;

        
        // Response initial value
        $this->responseData         = [
            'info'      => '',
            'message'   => '',
            'data'      => [],
        ];
        $this->responseStatusCode   = 400;
        $this->response             = $this->commonSvc->setResponse($this->responseData, $this->responseStatusCode);

    }

    /** ================================ Required functions for publication ================================ */

    #[Route('/api/v1/publication', methods: ['GET'], name: 'app_v1_publication')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication menu has been accessed!');

        $this->response = $this->commonSvc->setResponse([
            'info' => 'success',
            'message' => 'Success to access the publication`s API!',
            'data' => [
                'message' 	=> 'Welcome to publication`s API!',
                'date' 		=> date('Y-m-d'),
            ],
        ], 200);

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    #[Route('/api/v1/publications', methods: ['GET'], name: 'app_v1_publications')]
    public function getAll(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_publications.',
        ], 500);

        try {
            $params                     = ['flag_active' => true];
            $orderBy                    = ['updated_at' => 'DESC'];
            $paginator                  = $this->commonSvc->setPaginator($request);

            $publicationsEntity         = $entityManager->getRepository(Publication::class);
            $publicationTotalCount      = $publicationsEntity->count([]);
            $publicationsData           = $publicationsEntity->findBy(
                $params, $orderBy, $paginator->get('limit'), $paginator->get('offset')
            );

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publications data!',
                'data'     => $publicationsData,
                'count'    => $publicationTotalCount,
            ], 200);

            $this->logger->info('Get publications data: ');
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publications data!'
            ], 400);
            $this->logger->error(
                'Get publications data exception log: ' . $e->getMessage()
                    . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    #[Route('/api/v1/publications/{uuid}', methods: ['GET'], name: 'app_v1_publication_by_uuid')]
    public function getByUuid(ManagerRegistry $doctrine, string $uuid): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_publication_by_uuid.',
        ], 500);

        try {
            $params                     = ['uuid' => $uuid];
            $publicationRaw             = $entityManager->getRepository(Publication::class)->findOneBy(
                $params
            );
            $publication                = $this->commonSvc->normalizeObject($publicationRaw);
            $publication['meta_data']   = $publicationRaw->getPublicationMetas();

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication data!',
                'data'     => $publication,
            ], 200);

            $this->logger->info('Get publication data by UUID `' . $uuid . '`.');
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publication data!'
            ], 400);
            $this->logger->error(
                'Get publication data by UUID `' . $uuid
                    . '` exception log: ' . $e->getMessage()
                    . ', line: ' . $e->getLine(),
                [$e->getFile(), 'trace => ', $e->getTrace()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    /** =============================== Form metadata (Forms of Publication) =============================== */

    #[Route(
        '/api/v1/publications/form-meta-data/{publicationTypeCode}',
        methods: ['GET'],
        name: 'app_v1_publication_form_metadata_by_publication_type_code'
    )]
    public function getFormMetadataByPublicationTypeCode(
        ManagerRegistry $doctrine,
        string $publicationTypeCode
    ): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_publication_form_metadata_by_publication_type_code.',
        ], 500);

        $formVersionCode                = $this->request->query->get('form-version-code');

        try {
            // PublicationType
            $publicationTypeParams 			= ['publication_type_code' => $publicationTypeCode];
            $publicationType 				= $entityManager->getRepository(PublicationType::class)->
                findActiveOneByCode($publicationTypeCode);

            // FormVersion
            $formVersionsRaw                 = $publicationType->getFormVersions();
            $formVersion                    = $this->publicationSvc->getActiveFormVersionData($formVersionsRaw);
            $formVersionNormalize           = ($formVersion) ? $this->commonSvc->normalizeObject($formVersion) : null;

            // Get Forms raw data
            $formsRaw						= $this->publicationSvc->getAllFormMetaData(
                $formVersion->getForms()
            );

            $formsRawNormalizeCollection	= new ArrayCollection(
                $this->commonSvc->normalizeObject($formsRaw)
            );
            $forms							= $formsRawNormalizeCollection->map(function ($field) use ($entityManager) {
                $field['options']			= [];

                switch ($field['field_type']) {
                    case 'select':
                        $fieldOptions			= explode('-', $field['field_options']);
        
                        // Get options of select from database (Master data or terms of taxonomy)
                        $field['options']		= ($fieldOptions[0] === 'master') ? 
                            $entityManager->getRepository(PublicationForm::class)->getMasterData($fieldOptions[1]) : 
                            $entityManager->getRepository(PublicationForm::class)->getTaxonomyTerms($fieldOptions[1]) ;
                        break;

                    case 'autoselect':
                    case 'autocomplete':
                        $fieldOptions			= explode('-', $field['field_options']);
        
                        // Get options of autoselect from database (Master data or terms of taxonomy)
                        $field['options']		= ($fieldOptions[0] === 'master') ? 
                            $entityManager->getRepository(PublicationForm::class)
                                ->getMasterData($fieldOptions[1], 'ASC', 25) : 
                            $entityManager->getRepository(PublicationForm::class)
                                ->getTaxonomyTerms($fieldOptions[1], 'ASC', 25) ;
                        break;
                    
                    default: break;
                }

                return $field;
            })->toArray();

            // Set Forms in recursive pattern
            $formsNormilizeRecursive		= $this->dynamicFormSvc->setFields($forms, $formVersion->getGridSystem());

            // Set value of recursive data of Forms to `forms` property in FormVersion data
            if ($formVersion) {
                $formVersionNormalize['forms'] = $formsNormilizeRecursive;
            }

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication form metadata!',
                'data'     => $formVersionNormalize ?: [],
            ], 200);

            $this->logger->info('Get publication form: ' . $publicationType->getPublicationTypeName());
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publication form metadata!'
            ], 400);
            $this->logger->error(
                'Get form metadata exception log: ' . $e->getMessage() . ', line: ' . $e->getLine(),
                [$e->getFile(), $e->getTraceAsString()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    #[Route(
        '/api/v1/publications/{publicationUuid}/form-meta-data',
        methods: ['GET'],
        name: 'app_v1_publication_form_metadata_by_publication_uuid'
    )]
    public function getFormMetadataByPublicationUuid(
        ManagerRegistry $doctrine,
        string $publicationUuid
    ): JsonResponse
    {
        $entityManager                  = $doctrine->getManager();

        $this->response = $this->commonSvc->setResponse([
            'info' => 'error',
            'message' => 'No process is running in app_v1_publication_form_metadata_by_publication_uuid.',
        ], 500);

        $formVersionCode                = $this->request->query->get('form-version-code');

        try {
            // Publication
            $publication 				    = $entityManager->getRepository(Publication::class)
                ->findOneBy([
                    'uuid' => $publicationUuid
                ]);
            // PublicationMeta
            $publicationMetasRaw             = $entityManager->getRepository(PublicationMeta::class)
                ->findBy([
                    'flag_active'       => true,
                    'id_publication'    => $publication->getId()
                ], [
                    'order_position' => 'ASC'
                ]);

            // FormVersion
            $formVersion                    = $publication->getPublicationFormVersion();
            $formVersionNormalize           = ($formVersion) ? $this->commonSvc->normalizeObject($formVersion) : null;

            /** Get Forms (Metadata) data.
             *  Note: There is a bug in $publication->getPublicationMetas(),
             *        that always get one extra row of last ArrayCollection.
             *        So fix that with second direct query from PublicationMetaRepository
             */
            $formsRawNormalizeCollection	= new ArrayCollection(
                $this->commonSvc->normalizeObject($publicationMetasRaw)
            );
            $forms							= $formsRawNormalizeCollection->map(function ($field) use ($entityManager) {
                $field['options']			= [];

                switch ($field['field_type']) {
                    case 'select':
                        $fieldOptions			= explode('-', $field['field_options']);
        
                        // Get options of select from database (Master data or terms of taxonomy)
                        $field['options']		= ($fieldOptions[0] === 'master') ? 
                            $entityManager->getRepository(PublicationForm::class)->getMasterData($fieldOptions[1]) : 
                            $entityManager->getRepository(PublicationForm::class)->getTaxonomyTerms($fieldOptions[1]) ;
                        break;

                    case 'autoselect':
                    case 'autocomplete':
                        $fieldOptions			= explode('-', $field['field_options']);
        
                        // Get options of autoselect from database (Master data or terms of taxonomy)
                        $field['options']		= ($fieldOptions[0] === 'master') ? 
                            $entityManager->getRepository(PublicationForm::class)
                                ->getMasterData($fieldOptions[1], 'ASC', 25) : 
                            $entityManager->getRepository(PublicationForm::class)
                                ->getTaxonomyTerms($fieldOptions[1], 'ASC', 25) ;
                        break;

                    default: break;
                }

                return $field;
            })->toArray();

            // Set Forms in recursive pattern
            $formsNormilizeRecursive		= $this->dynamicFormSvc->setFields($forms, $formVersion->getGridSystem());

            // Set value of recursive data of Forms to `forms` property in FormVersion data
            if ($formVersion) {
                $formVersionNormalize['forms'] = $formsNormilizeRecursive;
            }

            // Response data
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'success',
                'message'  => 'Success to get publication form metadata!',
                'data'     => $formVersionNormalize ?: [],
            ], 200);

            $this->logger->info('Get publication form meta data by uuid `' . $publicationUuid . '`');
        } catch (\Exception $e) {
            $this->response = $this->commonSvc->setResponse([
                'info'     => 'error',
                'message'  => 'Error on get publication form metadata!'
            ], 400);
            $this->logger->error(
                'Get publication form meta data by uuid `' . $publicationUuid
                . '` exception log: ' . $e->getMessage()
                . ', line: ' . $e->getLine(),
                [$e->getFile(), $e->getTraceAsString()]
            );
        }

        return $this->json($this->response->get('data'), $this->response->get('status_code'));
    }

    /** ======================================== Template functions ======================================== */

    public function template(ManagerRegistry $doctrine)
    {
        $entityManager              = $doctrine->getManager();

        $publications               = $entityManager->getRepository(Publication::class)->
            findAll();
        $publicationGeneralType     = $entityManager->getRepository(PublicationGeneralType::class)->
            findOneBy(['id' => 1]);
        $publicationType            = $entityManager->getRepository(PublicationType::class)->
            findOneBy(['id' => 1]);
        $publicationStatus          = $entityManager->getRepository(PublicationStatus::class)->
            findOneBy(['id' => 1]);

        $this->responseData['data'] = $publications;
    }

}