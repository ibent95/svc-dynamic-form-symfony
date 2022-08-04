<?php

namespace App\Controller\V1;

use App\Entity\PublicationFormVersion;
use App\Entity\Publication;
use App\Entity\PublicationForm;
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
use Symfony\Component\String\UnicodeString;

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

        $formVersion['grid_system']['bootstrap'] = [
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
                'month_2' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'year_2' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'time_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'datetime_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'daterange_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'timerange_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'datetimerange_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'try_stepper_1_step_2' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'text_2' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'url_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'select_2' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'date_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'month_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'year_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'try_stepper_1_step_3' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'select_3' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'select_4' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'select_5' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'number_1' => [
                    'colspan' => 6,
                    'rowspan' => 1,
                ],
                'url_2' => [
                    'colspan' => 3,
                    'rowspan' => 1,
                ],
                'number_2' => [
                    'colspan' => 3,
                    'rowspan' => 1,
                ],
                'try_accordion_1' => [
                    'colspan' => 6,
                    'rowspan' => 12,
                ],
                'try_accordion_1_panel_1' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'text_3' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'url_3' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'number_3' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'try_accordion_1_panel_2' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'select_6' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'select_7' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'try_accordion_1_panel_3' => [
                    'colspan' => 12,
                    'rowspan' => 1,
                ],
                'select_8' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
                'select_9' => [
                    'colspan' => 4,
                    'rowspan' => 1,
                ],
            ],
        ];

        $formVersion['grid_system']['tailwind'] = [
            'type' => 'tailwind',
            'cols' => 12,
            'config' => [
                'text_1' => [
                    'colspan' => 3,
                ],
                'select_1' => [
                    'colspan' => 3,
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
                'text_2' => [
                    'colspan' => 6,
                ],
                'url_1' => [
                    'colspan' => 6,
                ],
                'select_2' => [
                    'colspan' => 6,
                ],
                'date_1' => [
                    'colspan' => 6,
                ],
                'month_1' => [
                    'colspan' => 6,
                ],
                'year_1' => [
                    'colspan' => 6,
                ],
                'try_stepper_1_step_3' => [
                    'colspan' => 12,
                ],
                'select_3' => [
                    'colspan' => 6,
                ],
                'select_4' => [
                    'colspan' => 6,
                ],
                'select_5' => [
                    'colspan' => 6,
                ],
                'number_1' => [
                    'colspan' => 6,
                ],
                'url_2' => [
                    'colspan' => 3,
                ],
                'number_2' => [
                    'colspan' => 3,
                ],
                'try_accordion_1' => [
                    'colspan' => 6,
                    'rowspan' => 12,
                ],
                'try_accordion_1_panel_1' => [
                    'colspan' => 12,
                ],
                'text_3' => [
                    'colspan' => 4,
                ],
                'url_3' => [
                    'colspan' => 4,
                ],
                'number_3' => [
                    'colspan' => 4,
                ],
                'try_accordion_1_panel_2' => [
                    'colspan' => 12,
                ],
                'select_6' => [
                    'colspan' => 4,
                ],
                'select_7' => [
                    'colspan' => 4,
                ],
                'try_accordion_1_panel_3' => [
                    'colspan' => 12,
                ],
                'select_8' => [
                    'colspan' => 4,
                ],
                'select_9' => [
                    'colspan' => 4,
                ],
            ],
        ];

        $field_configs = [
            'orientation'   => 'horizontal',
            'linear'        => true,
        ];

        $validation_config = [
            'pattern'       => 'horizontal',
            'error_message' => true,
        ];
    }

    /** ================================ Required functions for publication ================================ */

    #[Route('/api/v1/publication', methods: ['GET'], name: 'app_v1_publication')]
    public function index(): JsonResponse
    {
        $this->logger->info('The publication menu has been accessed!');

        $this->responseData['info'] 	= 'success';
        $this->responseData['message'] 	= 'Success to access the publication menu!';
        $this->responseData['data'] 	= [
            'message' 	=> 'Welcome to publication!',
            'date' 		=> date('Y-m-d'),
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

        $this->responseData['info']     = 'error';
        $this->responseData['message']  = '';
        $this->responseStatusCode       = 500;

        $formVersionCode                = $this->request->query->get('form-version-code');

        try {
            // PublicationType
            $publicationTypeParams 			= ['publication_type_code' => $publicationTypeCode];
            $publicationType 				= $entityManager->getRepository(PublicationType::class)->findOneBy($publicationTypeParams);

            // FormVersion
            $formVersionRaw 				= $publicationType->getFormVersion();
            $this->criteria->where($this->exprBuilder->eq('flag_active', true));
            if ($formVersionCode) $this->criteria->orWhere($this->exprBuilder->eq('id', $publicationType->getId()));
            $formVersionMatchFirst 			= $formVersionRaw->matching($this->criteria)->first();
            $formVersion 					= ($formVersionMatchFirst) ? $common->normalizeObject($formVersionMatchFirst) : NULL;

            // Get Forms raw data
            $formsRaw 						= $formVersionMatchFirst->getPublicationForms();
            $formsRawNormalizeCollection 	= new ArrayCollection($common->normalizeObject($formsRaw));
            $forms 							= $formsRawNormalizeCollection->map(function ($field) use ($entityManager) {
                $field['options'] 			= [];

                if ($field['field_type'] === 'select') {
                    $fieldOptions 			= explode('-', $field['field_options']);

                    // Get options of select from database (Master data or terms of taxonomy)
                    $field['options'] 		= ($fieldOptions[0] === 'master') ? $entityManager->getRepository(PublicationForm::class)->getMasterData($fieldOptions[1]) : $entityManager->getRepository(PublicationForm::class)->getTaxonomyTerms($fieldOptions[1]) ;
                }

                if ($field['field_type'] === 'autoselect') {
                    $fieldOptions 			= explode('-', $field['field_options']);

                    // Get options of autoselect from database (Master data or terms of taxonomy)
                    $field['options'] 		= ($fieldOptions[0] === 'master') ? $entityManager->getRepository(PublicationForm::class)->getMasterData($fieldOptions[1], 'ASC', 25) : $entityManager->getRepository(PublicationForm::class)->getTaxonomyTerms($fieldOptions[1], 'ASC', 25) ;
                }

                return $field;
            })->toArray();

            // Set Forms in recursive pattern
            $formsNormilizeRecursive 		= $common->setFields($forms, $formVersion['grid_system']);

            // Set value of recursive data of Forms to `forms` property in FormVersion data
            if ($formVersion) $formVersion['forms'] = $formsNormilizeRecursive;

            // Response data
            $this->responseData['data']     = $formVersion ?: [];
            $this->responseData['info']     = 'success';
            $this->responseData['message']  = 'Success to get publication form metadata!';
            $this->responseStatusCode       = 200;

            $this->logger->info('Get publication form: ' . $publicationType->getPublicationTypeName()); // . json_encode($this->responseData['data'])
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