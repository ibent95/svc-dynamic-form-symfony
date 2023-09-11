<?php

namespace App\Service;

use App\Entity\Publication;
use App\Entity\PublicationFormVersion;
use App\Entity\PublicationMeta;
use App\Entity\PublicationStatus;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PublicationService {
	private $doctrine;
	private $doctrineManager;
	private $logger;
    private $serializer;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $result;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, SerializerInterface $serializer, CommonService $commonSvc)
    {
		$this->doctrine 		= $doctrine;
		$this->doctrineManager 	= $doctrine->getManager();
		$this->logger			= $logger;
        $this->serializer       = $serializer;

        $this->exprBuilder 		= Criteria::expr();
        $this->criteria 		= new Criteria();

        // Other service`s
        $this->commonSvc 		= $commonSvc;
    }

    public function getOneFormVersionData(PersistentCollection $sourceData, $otherData = null): PublicationFormVersion
    {
        $data = [];
        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );
        
        if ($otherData) $this->criteria->orWhere($this->exprBuilder->eq('id', $otherData->getId()));
        
        // FormVersion data
        $data = $sourceData->matching($this->criteria)->first();

        return $data;
    }

    public function getOnePublicationMetaData(PersistentCollection $sourceData, String $id): PublicationMeta
    {
        $data = [];

        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('id', $id)
        );
        
        // FormVersion data
        $data = $sourceData->matching($this->criteria)->first();

        return $data;
    }

    public function getAllFormMetaData(PersistentCollection $sourceData, $otherData = null): Collection
    {
        $data = [];

        $this->criteria = $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );

        $data = $sourceData->matching($this->criteria);

        return $data;
    }

	/**
	 * This function response to get Main and Meta Data of input from Dynamic Form
	 */
	public function setDataByDynamicForm(Request $request, PublicationFormVersion $formVersion, Publication $publication = null): Publication | Array
	{
		$results    = [];

		$mainData   = $this->setMainData($request, $formVersion, $publication);
		$results    = $this->setMetaData($request, $formVersion, $mainData);

		return $results;
	}

	/**
	 * This function response to organize Main Data of input from Dynamic Form
	 */
	private function setMainData(Request | Array $request, PublicationFormVersion $formVersion, ?Publication $publication = null): Publication | Array
	{
        /**
         *  Initial value
         *  $results is an instance of Publication entity or $publication
         *  If $publication is not send than UPDATE mechanism is running, default is CREATE.
         */
		$results 				= $publication ?: new Publication();
		$requestData 			= (is_array($request)) ? $request : $request->request->all();

		// Get Form Configs of Main Data (if exists)
		$formConfigs 			= $formVersion->getForms();
		$formTypeFieldConfig 	= $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_form_type', true) ?: 'form_type';
		$titleFieldConfig 		= $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_title', true) ?: 'title';
		$publishDateFieldConfig = $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_publish_date', true) ?: 'publication_date';

		// Organize the Main Data
        $generalFormType 		= $formVersion->getPublicationType()->getPublicationGeneralType() ?: null;
		$formType    			= $formVersion->getPublicationType() ?: null;
		$formVersion 			= $formVersion ?: null;
		$formStatus 			= ($this->doctrineManager->getRepository(PublicationStatus::class))->findOneBy([
			'publication_status_code' => 'DRF'
		]) ?: null;
		$title 					= $requestData[
			(is_string($titleFieldConfig)) ? $titleFieldConfig : $titleFieldConfig->getFieldName()
		] ?? null;
		$publishDate 			= $requestData[
			(is_string($publishDateFieldConfig)) ? $publishDateFieldConfig : $publishDateFieldConfig->getFieldName()
		] ?? null;

		// Wrapp the Main Data
        if ($request->getMethod() == 'POST') {
            $results->setId($this->commonSvc->createUUIDShort());
            $results->setUuid($this->commonSvc->createUUID());
        }
        $results->setPublicationGeneralType($generalFormType);
        $results->setPublicationType($formType);
        $results->setPublicationFormVersion($formVersion);
        $results->setPublicationStatus($formStatus);
        $results->setTitle($title);
        $results->setPublicationDate($publishDate);
        $results->setFlagActive(true);
		
		return $results;
	}

	/**
	 * This function response to organize Meta Data of input from Dynamic Form
	 */
	private function setMetaData(Request $request, PublicationFormVersion $formVersion, Publication $publication): Publication | Array
	{
        // dump($publication);
        // Initial value
		$results = $publication;
		$requestData = (is_array($request)) ? $request : $request->request->all();
        $formConfigs = $this->commonSvc->normalizeObject($formVersion->getForms());

        // Remove the old Meta Data if there is Meta Data
        if (count($publication->getPublicationMetas()->toArray()) > 0) foreach ($publication->getPublicationMetas() as $metaIndex => $meta) {
            // dump('Meta ' . $metaIndex . ' : ', $meta);
            // $meta->setFlagActive(false);
            $results->removePublicationMetas($meta);
        }

        // Organize the new Meta Data (create or update)
        foreach ($formConfigs as $fieldIndex => $previousFieldConfig) {
            $meta       = new PublicationMeta();

            // Ids 
            $meta->setId($this->commonSvc->createUUIDShort());
            $meta->setUuid($this->commonSvc->createUUID());

            // Master data
            // $meta->setIdPublication($publication->getId());
            $meta->setPublication($publication);
            // $meta->setIdFormVersion($formVersion->getId());
            $meta->setFormVersion($formVersion);
            $meta->setIdFormParent($previousFieldConfig['id_form_parent']);

            // Field configs
            $meta->setFieldLabel($previousFieldConfig['field_label']);
            $meta->setFieldType($previousFieldConfig['field_type']);
            $meta->setFieldName($previousFieldConfig['field_name']);
            $meta->setFieldId($previousFieldConfig['field_id']);
            $meta->setFieldClass($previousFieldConfig['field_class']);
            $meta->setFieldPlaceholder($previousFieldConfig['field_placeholder']);
            $meta->setFieldOptions($previousFieldConfig['field_options']);
            $meta->setFieldConfigs($previousFieldConfig['field_configs']);
            $meta->setDescription($previousFieldConfig['description']);
            $meta->setOrderPosition($previousFieldConfig['order_position']);
            $meta->setValidationConfigs($previousFieldConfig['validation_configs']);
            $meta->setErrorMessage($previousFieldConfig['error_message']);
            $meta->setDependencyChild($previousFieldConfig['dependency_child']);
            $meta->setDependencyParent($previousFieldConfig['dependency_parent']);
            $meta->setFlagRequired($previousFieldConfig['flag_required']);
            $meta->setFlagFieldFormType($previousFieldConfig['flag_field_form_type']);
            $meta->setFlagFieldTitle($previousFieldConfig['flag_field_title']);
            $meta->setFlagFieldPublishDate($previousFieldConfig['flag_field_publish_date']);
            $meta->setFlagActive(true);
            $meta->setValue(null);
            $meta->setOtherValue(null);

            // Specifict handling by type of field
			switch ($previousFieldConfig['field_type']) {
				case 'panel':
                case 'accordion':
                case 'well':
                case 'step':
                case 'multiple':
                    /** 
                     * $meta->setValue(
                     *  $this->getMainDataByDynamicForm(
                     *      $requestData[
                     *          $fieldConfig->getFieldName()
                     *      ],
                     *      $fieldConfig->getChildren()
                     *  )
                     * ); 
                     */
                    break;

                case 'select':
                case 'autoselect':
                case 'autocomplete':
                    $meta->setValue(
                        $requestData[
                            $previousFieldConfig['field_name']
                        ]
                    );
                    $meta->setOtherValue([
                        'text' => $requestData[
                            $previousFieldConfig['field_name']
                        ],
                        'uuid' => $requestData[
                            $previousFieldConfig['field_name']
                        ],
                    ]);
                    break;

                default:
                    $meta->setValue(
                        $requestData[
                            $previousFieldConfig['field_name']
                        ]
                    );
                    break;
			}

            // Push the Meta Data to Main Data
            $results->addPublicationMetas($meta);
		}
        // dump($results);
		return $results;
	}

	private function setFieldConfigFromFormConfigs(PersistentCollection $formConfigs, string $key, mixed $value): object | false
	{
        $this->criteria->where(
			$this->exprBuilder->eq($key, $value)
		);

		$result = $formConfigs->matching($this->criteria)->first();

		return $result;
	}

	/**
	 * [Experimental] This function response to organize all type of Data input from Dynamic Form
	 */
	public function dynamicDataAdjustment(Request | Array $request, Array $formConfigs): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$requestData = (is_array($request)) ? $request : $request->request->all();

		foreach ($formConfigs as $fieldIndex => $fieldConfig) {

			if ($fieldConfig->getFieldName()) switch ($fieldConfig->getFieldType()) {
				case 'step':
				case 'multiple':
					$results->add([
						$fieldConfig->getFieldName() => $this->dynamicDataAdjustment($requestData[$fieldConfig->getFieldName()], $fieldConfig->getChildren())
					]);
					break;

				case 'select':
				case 'autoselect':
				case 'autocomplete':
					$results->add([
						$fieldConfig->getFieldName() => $requestData[$fieldConfig->getFieldName()]
					]);
					break;

				default:
					$results->add([
						$fieldConfig->getFieldName() => $requestData[$fieldConfig->getFieldName()]
					]);
					break;
			}
		}

		return $results;
	}

}