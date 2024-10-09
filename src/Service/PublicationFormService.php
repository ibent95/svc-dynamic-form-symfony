<?php

namespace App\Service;

use App\Entity\Publication;
use App\Entity\PublicationForm;
use App\Entity\PublicationFormVersion;
use App\Entity\PublicationMeta;
use App\Entity\PublicationStatus;
use App\Entity\TemporaryFileUpload;
use App\Repository\PublicationFormRepository;
use App\Repository\PublicationFormVersionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

use function PHPUnit\Framework\isEmpty;

class PublicationFormService {
    private $publicationFormRepo;
    private $publicationFormVersionRepo;

	private $doctrine;
	private $doctrineManager;
	private $logger;
    private $serializer;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $results;

    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        CommonService $commonSvc,
        PublicationFormRepository $publicationFormRepo,
        PublicationFormVersionRepository $publicationFormVersionRepo,
    )
    {
		$this->doctrine 		= $doctrine;
		$this->doctrineManager 	= $doctrine->getManager();
		$this->logger			= $logger;
        $this->serializer       = $serializer;

        $this->exprBuilder 		= Criteria::expr();
        $this->criteria 		= new Criteria();

        // Other service`s
        $this->commonSvc 		= $commonSvc;
        $this->results          = null;

        $this->publicationFormRepo = $publicationFormRepo;
        $this->publicationFormVersionRepo = $publicationFormVersionRepo;
    }

    public function getQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): ?array
    {
        $this->results = [
            'count' => 0,
            'data' => []
        ];

        $params = new ArrayCollection($parameters);
        if (isset($parameters['uuid_publication_form_version'])) {
            $params->set(
                'id_form_version',
                $parameters['uuid_publication_form_version']
                    ? $this->publicationFormVersionRepo
                        ->findOneBy([
                            'uuid' => $parameters['uuid_publication_form_version']
                        ])
                        ->getId()
                    : null
            );
            $params->remove('uuid_publication_form_version');
        }
        $params = $params->filter(function (mixed $value, mixed $key) {
            return !$this->commonSvc->isEmptyValue($value);
        });

        /** @var Query $data */
        $data = $this->publicationFormRepo->getQueryBuilderAll($params->toArray(), $orderBy, $maxResults, $firstResult);

        if ($data) {
            $this->results = [
                'count' => $this->publicationFormRepo->getCount(),
                'data'  => $data->getResult(), // $data->fetchAllAssociative(),
            ];
        }

        return $this->results;
    }

    public function getRawQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): ?array
    {
        $this->results = [
            'count' => 0,
            'data' => []
        ];

        $params = new ArrayCollection($parameters);
        if (isset($parameters['uuid_publication_form_version'])) {
            $params->set(
                'id_form_version',
                $parameters['uuid_publication_form_version']
                    ? $this->publicationFormVersionRepo
                        ->findOneBy([
                            'uuid' => $parameters['uuid_publication_form_version']
                        ])
                        ->getId()
                    : null
            );
            $params->remove('uuid_publication_form_version');
        }
        $params = $params->filter(function (mixed $value, mixed $key) {
            return !$this->commonSvc->isEmptyValue($value);
        });

        /** @var Result $data */
        $data = $this->publicationFormRepo->getRawQueryBuilderAll($params->toArray(), $orderBy, $maxResults, $firstResult);

        if ($data) {
            $this->results = [
                'count' => $this->publicationFormRepo->getCount(),
                'data'  => $data->fetchAllAssociative(),
            ];
        }

        return $this->results;
    }

    public function findOneBy(array $parameters): PublicationForm
    {
        $this->results = $this->publicationFormRepo->findOneBy($parameters);
        return $this->results;
    }

    public function save(Request $request): PublicationForm
    {
        $requestAll     = $request->request->all();

        $this->results  = ($request->get('uuid'))
            ? $this->publicationFormRepo->findOneBy(['uuid' => $requestAll['uuid']])
            : new PublicationForm() ;

        // Master Data
        $formVersion = ($request->get('uuid_form_version'))
            ? $this->publicationFormVersionRepo->findOneBy([
                'uuid' => $requestAll['uuid_form_version']
            ])
            : null ;
        $formParent = ($request->get('uuid_form_parent'))
            ? $this->publicationFormRepo->findOneBy([
                'uuid' => $requestAll['uuid_form_parent']
            ])
            : null ;

        $fieldConfigs            = ($request->get('field_configs')) ? json_decode($requestAll['field_configs'], true) : null;
        $validationConfigs       = ($request->get('validation_configs')) ? json_decode($requestAll['validation_configs'], true) : null;
        $dependencyChildConfigs  = ($request->get('dependency_child')) ? json_decode($requestAll['dependency_child'], true) : null;
        $dependencyParentConfigs = ($request->get('dependency_parent')) ? json_decode($requestAll['dependency_parent'], true) : null;

        if (empty($requestAll['uuid']) && $request->getMethod() === 'POST') {
            $this->results->setId($this->commonSvc->createIDTimestamp());
            $this->results->setUuid($this->commonSvc->createUUID());
        }

        if ($formVersion) {
            $this->results->setFormVersion($formVersion);
        }

        if ($formParent) {
            $this->results->setFormParent($formParent);
        }

        $this->results->setFieldLabel($request->get('field_label'));
        $this->results->setFieldType($request->get('field_type'));
        $this->results->setFieldName($request->get('field_name'));
        $this->results->setFieldId($request->get('field_id'));
        $this->results->setFieldClass($request->get('field_class'));
        $this->results->setFieldPlaceholder($request->get('field_placeholder'));
        $this->results->setFieldOptions($request->get('field_options'));
        $this->results->setDescription($request->get('description'));
        $this->results->setOrderPosition($request->get('order_position'));
        $this->results->setFieldConfigs($fieldConfigs);
        $this->results->setValidationConfigs($validationConfigs);
        $this->results->setDependencyChild($dependencyChildConfigs);
        $this->results->setDependencyParent($dependencyParentConfigs);
        $this->results->setErrorMessage($request->get('error_message'));
        $this->results->setFlagRequired($request->get('flag_required', false));
        $this->results->setFlagFieldFormType($request->get('flag_field_form_type', false));
        $this->results->setFlagFieldTitle($request->get('flag_field_title', false));
        $this->results->setFlagFieldPublishDate($request->get('flag_field_publish_date', false));
        $this->results->setFlagActive($request->get('flag_active', true));

        return $this->results;
    }

    public function remove(Request $request, string $uuid): mixed
    {
        return $this->results;
    }

    public function disable(Request $request, string $uuid): ?PublicationForm
    {
        $requestAll     = $request->request->all();

        $this->results  = ($uuid)
            ? $this->publicationFormRepo->findOneBy(['uuid' => $uuid])
            : null ;

        if (!$this->results) {
            throw new \Exception("The publication form configuration is not found with UUID: $uuid.", 400);
        }

        $this->results->setFlagActive(false);

        return $this->results;
    }

}