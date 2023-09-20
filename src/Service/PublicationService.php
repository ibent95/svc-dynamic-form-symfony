<?php

namespace App\Service;

use App\Entity\Publication;
use App\Entity\PublicationForm;
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

    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        CommonService $commonSvc
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
        $this->result           = null;
    }

    public function getActiveFormVersionData(
        PersistentCollection $sourceData,
        $otherData = null
    ): PublicationFormVersion
    {
        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );
        
        if ($otherData) {
            $this->criteria->orWhere($this->exprBuilder->eq('id', $otherData->getId()));
        }

        return $sourceData->matching($this->criteria)->first();
    }

    public function getPublicationFormDataById(
        PersistentCollection | ArrayCollection $sourceData,
        String $id
    ): PublicationForm | FALSE
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

    public function getPublicationFormDataByUuid(
        PersistentCollection | ArrayCollection $sourceData,
        String $uuid
    ): PublicationForm | FALSE
    {
        $data = [];

        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('uuid', $uuid)
        );
        
        // FormVersion data
        $data = $sourceData->matching($this->criteria)->first();

        return $data;
    }

    public function getPublicationMetaDataById(
        PersistentCollection | ArrayCollection $sourceData,
        String $id
    ): PublicationMeta | FALSE
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

    public function getPublicationMetaDataByUuid(
        PersistentCollection | ArrayCollection $sourceData,
        String $uuid
    ): PublicationMeta | FALSE
    {
        $data = [];

        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('uuid', $uuid)
        );
        
        // FormVersion data
        $data = $sourceData->matching($this->criteria)->first();

        return $data;
    }

    public function getAllFormMetaData(PersistentCollection | ArrayCollection $sourceData): Collection
    {
        $this->criteria = $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );

        return $sourceData->matching($this->criteria);
    }

	/**
	 * This function response to get Main and Meta Data of input from Dynamic Form
	 */
	public function setDataByDynamicForm(
        Request $request,
        PublicationFormVersion $formVersion,
        Publication $publication = null
    ): Publication | array
	{
		$results    = [];

		$mainData   = $this->setMainData($request, $formVersion, $publication);
		$results    = $this->setMetaData($request, $formVersion, $mainData);

		return $results;
	}

	/**
	 * This function response to organize Main Data of input from Dynamic Form
	 */
	private function setMainData(
        Request | array $request,
        PublicationFormVersion $formVersion,
        ?Publication $publication = null
    ): Publication | array
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
		$formTypeFieldConfig 	= $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_form_type', true)
            ?: 'form_type';
		$titleFieldConfig 		= $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_title', true)
            ?: 'title';
		$publishDateFieldConfig = $this->setFieldConfigFromFormConfigs($formConfigs, 'flag_field_publish_date', true)
            ?: 'publication_date';

		// Organize the Main Data
        $generalFormType 		= $formVersion->getPublicationType()->getPublicationGeneralType() ?: null;
		$formType    			= $formVersion->getPublicationType() ?: null;
		$formVersion 			= $formVersion ?: null;
		$formStatus 			= ($this->doctrineManager->getRepository(PublicationStatus::class))->findOneBy([
			'publication_status_code' => 'DRF'
		]) ?: null;
        $title 					=  null;
		$publishDate 			= null;

        foreach ($requestData['meta_data'] as $metaDataIndex => $metaData) {
            switch ($metaData['field_name']) {
                case (is_string($titleFieldConfig)) ?
                    $titleFieldConfig :
                    $titleFieldConfig->getFieldName():

                    $title          = $metaData['value'];
                    break;

                case (is_string($publishDateFieldConfig)) ? 
                    $publishDateFieldConfig :
                    $publishDateFieldConfig->getFieldName():

                    $publishDate    = $metaData['value'];
                    break;

                default: break;
            }
        }

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
	private function setMetaData(
        Request $request,
        PublicationFormVersion $formVersion,
        Publication $publication
    ): Publication | array
	{
        // Initial value
		$results        = $publication;
		$requestData    = (is_array($request)) ? $request : $request->request->all();

        // Organize the new Meta Data (create or update)
        if ($request->getMethod() == 'POST') {
            $results    = $this->updateMetaData($requestData, $formVersion, $publication);
        }

        if ($request->getMethod() == 'PUT') {
            $results    = $this->updateMetaData($requestData, $formVersion, $publication);
        }

		return $results;
	}

    private function updateMetaData(
        array $requestData,
        PublicationFormVersion $formVersion,
        Publication $publication
    ) : Publication | array
    {
        $results = $publication;
        $metaDataConfigs = $publication->getPublicationMetas();
        $formConfigs = $formVersion->getForms();

        /**
         * Remove the old Meta Data if there is Meta Data.
         * There are two options:
         * 1. Set active flag to false (0)
         * 2. Remove existing data from PersistenceCollection 
         *    array ($results->removePublicationMetas($metaDataConfig);) 
         */
        if (count($metaDataConfigs->toArray()) > 0) {
            foreach ($metaDataConfigs as $metaDataConfigIndex => $metaDataConfig) {
                $metaDataConfig->setFlagActive(false);
            }
        }

        // Organize data
        foreach ($requestData['meta_data'] as $metaDataIndex => $metaData) {
            /**
             * Initial value:
             * If there is Meta Data in previous Publication Meta Data, then use it as initial value.
             * Other than that, set Meta Data by Form Configuration.
             */
            $metaDataConfig     = ($this->getPublicationMetaDataByUuid($metaDataConfigs, $metaData['uuid'])) ?
                $this->getPublicationMetaDataByUuid(
                    $metaDataConfigs,
                    $metaData['uuid']
                )
            :
                $this->setPublicationMetaDataByPublicationFormConfig(
                    new PublicationMeta(),
                    $publication,
                    $formVersion,
                    $this->getPublicationFormDataByUuid($formConfigs, $metaData['uuid']),
                    $metaData
                )
            ;

            $metaDataConfig->setFlagActive(true);

            // Specifict handling by type of field
			switch ($metaDataConfig->getFieldType()) {
				case 'panel':
                case 'accordion':
                case 'well':
                case 'step':
                case 'multiple':
                    /** 
                     * $metaDataConfig->setValue(
                     *  $this->getMainDataByDynamicForm(
                     *      $metaData['value'],
                     *      $fieldConfig->getChildren()
                     *  )
                     * ); 
                     */
                    break;

                case 'multiple_select':
                case 'multiple_autoselect':
                case 'multiple_autocomplete': break;

                case 'select':
                case 'autoselect':
                case 'autocomplete':
                    $metaDataConfig->setValue(
                        $metaData['value']
                    );
                    $metaDataConfig->setOtherValue(
                        $metaData['other_value']
                    );
                    break;

                case 'file':
                case 'image':
                    $metaDataConfig->setValue(
                        $metaData['value']
                    );
                    $metaDataConfig->setOtherValue([
                        'file_name' => null,
                        'path' => null,
                        'url' => null
                    ]);
                    break;

                case 'date':
                case 'month':
                case 'year':
                case 'time':
                case 'datetime':
                case 'owl-date':
                case 'owl-month':
                case 'owl-year':
                case 'owl-time':
                case 'owl-datetime':
                    $metaDataConfig->setValue(
                        $metaData['value']
                    );
                    $metaDataConfig->setOtherValue([
                        'value' => null,
                        'text' => null
                    ]);
                    break;

                case 'daterange':
                case 'timerange':
                case 'datetimerange':
                case 'owl-daterange':
                case 'owl-timerange':
                case 'owl-datetimerange': break;

                case 'radio':
                case 'checkbox':
                case 'mask':
                case 'mask_full_time':
                case 'url':
                default:
                    $metaDataConfig->setValue(
                        $metaData['value']
                    );
                    break;
			}

            // Push the Meta Data to Main Data
            $results->addPublicationMetas($metaDataConfig);
		}

        return $results;
    }

    private function setPublicationMetaDataByPublicationFormConfig(
        PublicationMeta $publicationMeta,
        Publication $publication,
        PublicationFormVersion $formVersion,
        PublicationForm $fieldConfig,
        array $metaData
    ) : PublicationMeta
    {
        $results = $publicationMeta;

        /**
         * Organize data
         */

        // Ids 
        $results->setId($this->commonSvc->createUUIDShort());
        $results->setUuid($this->commonSvc->createUUID());

        // Master data
        $results->setIdPublication($publication->getId());
        $results->setPublication($publication);
        $results->setIdFormVersion($formVersion->getId());
        $results->setFormVersion($formVersion);
        $results->setIdFormParent($fieldConfig->getIdFormParent());

        // Field configs
        $results->setFieldLabel($fieldConfig->getFieldLabel());
        $results->setFieldType($fieldConfig->getFieldType());
        $results->setFieldName($fieldConfig->getFieldName());
        $results->setFieldId($fieldConfig->getFieldId());
        $results->setFieldClass($fieldConfig->getFieldClass());
        $results->setFieldPlaceholder($fieldConfig->getFieldPlaceholder());
        $results->setFieldOptions($fieldConfig->getFieldOptions());
        $results->setFieldConfigs($fieldConfig->getFieldConfigs());
        $results->setDescription($fieldConfig->getDescription());
        $results->setOrderPosition($fieldConfig->getOrderPosition());
        $results->setValidationConfigs($fieldConfig->getValidationConfigs());
        $results->setErrorMessage($fieldConfig->getErrorMessage());
        $results->setDependencyChild($fieldConfig->getDependencyChild());
        $results->setDependencyParent($fieldConfig->getDependencyParent());
        $results->setFlagRequired($fieldConfig->getFlagRequired());
        $results->setFlagFieldFormType($fieldConfig->getFlagFieldFormType());
        $results->setFlagFieldTitle($fieldConfig->getFlagFieldTitle());
        $results->setFlagFieldPublishDate($fieldConfig->getFlagFieldPublishDate());
        $results->setFlagActive(true);
        $results->setValue(null);
        $results->setOtherValue(null);

        // Specifict handling by type of field
        switch ($fieldConfig->getFieldType()) {
            case 'panel':
            case 'accordion':
            case 'well':
            case 'step':
            case 'multiple':
                /** 
                 * $results->setValue(
                 *  $this->getMainDataByDynamicForm(
                 *      $metaData['value'],
                 *      $fieldConfig->getChildren()
                 *  )
                 * ); 
                 */
                break;

            case 'multiple_select':
            case 'multiple_autoselect':
            case 'multiple_autocomplete': break;

            case 'select':
            case 'autoselect':
            case 'autocomplete':
                $results->setValue(
                    $metaData['value']
                );
                $results->setOtherValue(
                    $metaData['other_value']
                );
                break;

            case 'file':
            case 'image':
                $results->setValue(
                    $metaData['value']
                );
                $results->setOtherValue([
                    'file_name' => null,
                    'path' => null,
                    'url' => null
                ]);
                break;

            case 'date':
            case 'month':
            case 'year':
            case 'time':
            case 'datetime':
            case 'owl-date':
            case 'owl-month':
            case 'owl-year':
            case 'owl-time':
            case 'owl-datetime':
                $results->setValue(
                    $metaData['value']
                );
                $results->setOtherValue([
                    'value' => null,
                    'text' => null
                ]);
                break;

            case 'daterange':
            case 'timerange':
            case 'datetimerange':
            case 'owl-daterange':
            case 'owl-timerange':
            case 'owl-datetimerange': break;

            case 'radio':
            case 'checkbox':
            case 'mask':
            case 'mask_full_time':
            case 'url':
            default:
                $results->setValue(
                    $metaData['value']
                );
                break;
        }

        return $results;
    }

	private function setFieldConfigFromFormConfigs(
        PersistentCollection $formConfigs,
        string $key,
        mixed $value
    ): object | false
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
	public function dynamicDataAdjustment(Request | array $request, array $formConfigs): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$requestData = (is_array($request)) ? $request : $request->request->all();

		foreach ($formConfigs as $fieldIndex => $fieldConfig) {

			if ($fieldConfig->getFieldName()) switch ($fieldConfig->getFieldType()) {
				case 'step':
				case 'multiple':
					$results->add([
						$fieldConfig->getFieldName() => $this->dynamicDataAdjustment(
                            $requestData[$fieldConfig->getFieldName()],
                            $fieldConfig->getChildren()
                        )
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