<?php

namespace App\Service;

use App\Entity\PublicationStatus;
use App\Repository\PublicationStatusRepository;
use App\Service\CommonService;

use Psr\Log\LoggerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class DynamicFormService
{
	private $doctrine;
	private $doctrineManager;
	private $logger;
	private $mainFieldColumns;
	private $mainFieldColumnMappingInMeta;
    private $exprBuilder;
    private $criteria;
	private $commonSvc;

	public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, CommonService $commonSvc) {
		$this->doctrine 						= $doctrine;
		$this->doctrineManager 					= $doctrine->getManager();

		$this->logger							= $logger;
		$this->commonSvc						= $commonSvc;

		$this->mainFieldColumns					= [
			'publication_general_type_id',
			'publication_type_id',
			'publication_form_version_id',
			'publication_status_id',
			'title',
			'id_publication_general_type',
			'id_publication_type',
			'id_publication_form_version',
			'id_publication_status',
			'publication_date',
		];
		$this->mainFieldColumnMappingInMeta		= [
			[
				'main_column_name' => 'publication_general_type_id',
				'meta_column_name' => 'flag_publication_general_type_id',
				'request_property' => 'publication_general_type_uuid',
			],
			[
				'main_column_name' => 'publication_type_id',
				'meta_column_name' => 'flag_publication_type_id',
				'request_property' => 'publication_type_uuid',
			],
			[
				'main_column_name' => 'publication_form_version_id',
				'meta_column_name' => 'flag_publication_form_version_id',
				'request_property' => 'publication_form_version_uuid',
			],
			[
				'main_column_name' => 'publication_status_id',
				'meta_column_name' => 'flag_publication_status_id',
				'request_property' => 'publication_status_uuid',
			],
			[
				'main_column_name' => 'title',
				'meta_column_name' => 'flag_title',
				'request_property' => 'title',
			],
			[
				'main_column_name' => 'id_publication_general_type',
				'meta_column_name' => 'flag_id_publication_general_type',
				'request_property' => 'publication_general_type_uuid',
			],
			[
				'main_column_name' => 'id_publication_type',
				'meta_column_name' => 'flag_id_publication_type',
				'request_property' => 'publication_type_uuid',
			],
			[
				'main_column_name' => 'id_publication_form_version',
				'meta_column_name' => 'flag_id_publication_form_version',
				'request_property' => 'publication_form_version_uuid',
			],
			[
				'main_column_name' => 'id_publication_status',
				'meta_column_name' => 'flag_id_publication_status',
				'request_property' => 'publication_status_uuid',
			],
			[
				'main_column_name' => 'publication_date',
				'meta_column_name' => 'flag_publication_date',
				'request_property' => 'publication_date',
			],
		];
        $this->exprBuilder 		= Criteria::expr();
        $this->criteria 		= new Criteria();
	}

	/**
	 * Function to set grid system, organize array recursive data from flat array data, also clean the array recursive data
	 */
	function setFields(Array $flatArray, Array $gridSystem = NULL): Array
	{
		// Set the array from flat to recursive
		$recursiveData = $this->setRecursiveFields('id', 'id_form_parent', $flatArray, NULL, $gridSystem);

		// Clean property of recursive data
		$data = $this->cleanFields(["id", "id_form_version", "id_form_parent", "order", "flag_judul_publikasi", "flag_tgl_publikasi", "flag_peran"], $recursiveData);

		// Return data
		return $data;
	}

	/**
	 * Recursive function to set array recursive data from flat array data
	 */
	function setRecursiveFields(String $uniqueField, String $idParentField, Array &$elements = [], String $parentId = NULL, Array $gridSystem = NULL): Array
	{
		$branch = [];
		foreach ($elements as $element) {

			// Set grid config
			$element['field_configs'] = $this->setGridConfig($element, $gridSystem);

			/**
			 * Main command in this recursive function.
			 * If the id of current element equal to the id of parent element,
			 * then run recursive function and other command.
			 */
			if ($element[$idParentField] == $parentId) {
				$exprBuilder			= Criteria::expr();

				// Run recursive search
				$children				= $this->setRecursiveFields($uniqueField, $idParentField, $elements, $element[$uniqueField], $gridSystem);

				//
				if ($element['dependency_parent'] || $element['dependency_child']) {
					$dependencyParent				= json_decode($element['dependency_parent']);
					$dependencyChild				= json_decode($element['dependency_child']);

					$element['dependency_parent']	= (is_array($dependencyParent)) ? $dependencyParent : $element['dependency_parent'];
					$element['dependency_child']	= (is_array($dependencyChild))	? $dependencyChild	: $element['dependency_child'];
				}

				$element['children'] = ($children) ? $children : [];
				if ($element['field_type'] == 'multiple') {
					//if ($element['field_name'] === 'keanggotaan') $element['children'][] = [
					//	"id" => NULL, "id_publikasi_form_versi" => NULL,
					//	"id_publikasi_form_induk" => NULL, "field_label" => NULL,
					//	"id_field" => NULL, "field_type" => "hidden",
					//	"class_field" => NULL, "field_name" => "nik_keanggotaan",
					//	"field_placeholder" => NULL, "field_options" => NULL,
					//	"default_value" => NULL, "validation_config" => NULL,
					//	"tabel" => "publikasi_meta", "flag_multiple_field" => 0,
					//	"order" => 1, "uuid_form" => NULL,
					//	"children" => []
					//];

					$element['children'][] = [
						"field_label"			=> NULL,
						"field_id"				=> NULL,
						"field_type"			=> "hidden",
						"field_class"			=> NULL,
						"field_name"			=> "uuid_" . $element['field_name'],
						"field_placeholder"		=> NULL,
						"field_options"			=> NULL,
						"default_value"			=> NULL,
						"validation_config"		=> NULL,
						"flag_multiple_field"	=> 0,
						"order_position"		=> 1,
						"uuid"					=> NULL,
						"options"				=> [],
						"children"				=> []
					];
				}

				// Expression builder for get multiple field in children data
				$criteria = $exprBuilder->orX(
					$exprBuilder->eq('field_type', 'multiple'),
					$exprBuilder->eq('field_type', 'stepper')
				);

				// Search multiple field in children data
				$childrenCollection				= new ArrayCollection($children);
				$childrenCollectionMatch		= $childrenCollection->matching(new Criteria($criteria));
				$childrenCount					= $childrenCollectionMatch->count();

				// Define flag_multiple_field set it`s value to true if multiple field found in children data
				$element['flag_multiple_field'] = (($element['field_type'] == 'wizard' || $element['field_type'] == 'stepper') && (count($children) > 0) && ($childrenCount > 0)) ? TRUE : FALSE;

				// Handling or setting grid configuration

				$branch[]						= $element;
				//unset($elements[$element[$uniqueField]]);

			}
		}
		return $branch;
	}

	/**
	 * Recursive function to remove unwanted property in array recursive data
	 */
	function cleanFields(Array $acceptableFields, Array &$elements = []): Array
	{
		// Initial result variable
		$result = [];

		// Loop of elements or data (forms metadata)
		foreach ($elements as $element) {

			// Define filtered property of element variable
			$elementCollections = [];

			// Procceed to filter element property
			foreach ($element as $key => $value) {
				if (!in_array($key, $acceptableFields)) $elementCollections[$key] = $value;
			}

			// Search for children data to filter it recursively
			if (isset($elementCollections['children']) && (count($elementCollections['children']) > 0)) {
				$children = $this->cleanFields($acceptableFields, $elementCollections['children']);
				$elementCollections['children'] = ($children) ? $children : [];
			}

			$result[] = $elementCollections;
		}
		return $result;
	}

	/**
	 * Set grid config (grid system)
	 */
	function setGridConfig(Array $element, $gridSystem): Array | NULL
	{
		// Initial result data
		$result = NULL;

		// Get element`s or field`s grid system config
		$elementGridConfig = $gridSystem['config'][$element['field_id']] ?? NULL;

		// Merge current element`s or field`s config with grid system config
		$result = ($element && $elementGridConfig) ? array_merge($element['field_configs'] ?: [], $elementGridConfig ?: []) : $element['field_configs'];

		return $result;
	}

	/**
	 * This function response to get Main and Meta Data of input from Dynamic Form
	 */
	public function getDataByDynamicForm(Request $request, $formVersion = null, string $titleFieldName = 'title', string $generalFormTypeIdFieldName = null, string $formTypeIdFieldName = null, string $formVersionIdFieldName = null, string $formStatusIdFieldName = null, string $publishDateFieldName = null): Array
	{
		$results = [];

		$mainData = $this->getMainDataByDynamicForm($request, $formVersion, $titleFieldName, $generalFormTypeIdFieldName, $formTypeIdFieldName, $formVersionIdFieldName, $formStatusIdFieldName, $publishDateFieldName);
		$metaData = $this->getMetaDataByDynamicForm($request, $formVersion, $mainData);

		$results = [
			'main_data' => $mainData,
			'meta_data' => $metaData
		];

		return $results;
	}

	/**
	 * This function response to organize Main Data of input from Dynamic Form
	 */
	public function getMainDataByDynamicForm(Request | Array $request, $formVersion, string $titleFieldName = 'title', string $generalFormTypeIdFieldName = null, string $formTypeIdFieldName = null, string $formVersionIdFieldName = null, string $formStatusIdFieldName = null, string $publishDateFieldName = null): Array
	{
		$results 				= [];

		$requestData 			= (is_array($request)) ? $request : $request->request->all();

		// Get Form Configs of Main Data (if exists)
		$formConfigs 			= $formVersion->getForms();
		$formTypeFieldConfig 	= $this->getFieldConfigFromFormConfigs($formConfigs, 'flag_field_form_type', true) ?: 'form_type';
		$titleFieldConfig 		= $this->getFieldConfigFromFormConfigs($formConfigs, 'flag_field_title', true) ?: 'title';
		$publishDateFieldConfig = $this->getFieldConfigFromFormConfigs($formConfigs, 'flag_field_publish_date', true) ?: 'publication_date';

		// Organize the Main Data
		$formTypeId 			= $requestData[
			(is_string($formTypeFieldConfig)) ?
				$formTypeFieldConfig :
				$formTypeFieldConfig->getFieldName()
		] ?? null;
		$generalFormTypeId 		= $formVersion->getPublicationType()->getPublicationGeneralType()->getId() ?? null;
		$formVersionId 			= $formVersion->getId() ?? null;
		$formStatusId 			= ($this->doctrineManager->getRepository(PublicationStatus::class))->findOneBy([
			'publication_status_code' => 'DRF'
		])->getId() ?? null;
		$title 					= $requestData[
			(is_string($titleFieldConfig)) ?
				$titleFieldConfig :
				$titleFieldConfig->getFieldName()
		] ?? null;
		$publishDate 			= $requestData[
			(is_string($publishDateFieldConfig)) ?
				$publishDateFieldConfig :
				$publishDateFieldConfig->getFieldName()
		] ?? null;

		// Wrapp the Main Data
		$results = [
			'id' 						=> $this->commonSvc->createUUIDShort(),
			'uuid' 						=> $this->commonSvc->createUUID(),
			$formTypeIdFieldName 		=> $formTypeId 			?? null,
			$generalFormTypeIdFieldName => $generalFormTypeId 	?? null,
			$formVersionIdFieldName 	=> $formVersionId 		?? null,
			$formStatusIdFieldName 		=> $formStatusId 		?? null,
			$titleFieldName 			=> $title 				?? null,
			$publishDateFieldName 		=> $publishDate 		?? null,
		];
		
		return $results;
	}

	/**
	 * This function response to organize Meta Data of input from Dynamic Form
	 */
	public function getMetaDataByDynamicForm(Request $request, $formVersion, Array $mainData): Array
	{
		$results = [];

		$requestData = (is_array($request)) ? $request : $request->request->all();

		$formConfigs = $formVersion->getForms()->toArray();
		$formConfigsArray = $this->commonSvc->normalizeObject($formVersion->getForms());

		foreach ($formConfigs as $fieldIndex => $fieldConfig) {
			$item = $formConfigsArray[$fieldIndex];
			$item['id'] = $this->commonSvc->createUUIDShort();
			$item['uuid'] = $this->commonSvc->createUUID();
			$item['id_publication'] = $mainData['id'];
			$item['value'] = null;
			$item['other_value'] = null;

			switch ($fieldConfig->getFieldType()) {
				case 'panel':
					case 'accordion':
					case 'well':
					case 'step':
					case 'multiple':
						$item['value'] = $this->getMainDataByDynamicForm($requestData[$fieldConfig->getFieldName()], $fieldConfig->getChildren());
						break;
	
					case 'select':
					case 'autoselect':
					case 'autocomplete':
						$item['value'] = $requestData[$fieldConfig->getFieldName()];
						$item['other_value'] = $requestData[$fieldConfig->getFieldName()];
						break;
	
					default:
						$item['value'] = $requestData[$fieldConfig->getFieldName()];
						break;
			}
			$results[] = $item;
		}

		dd($results);

		return $results;
	}
	
	function getFieldConfigFromFormConfigs(PersistentCollection $formConfigs, string $key, mixed $value): object | false
	{

		/**
		 * [Experimental]
		 * return $this->getSrcFiles()->filter(function(SrcFile $srcFile) {
		 *  return ($srcFile->getKind() === 'master' && $srcFile->getSrcSheet()->getName() === 'MainData');
		 * });
		 */
		

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