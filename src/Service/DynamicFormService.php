<?php

namespace App\Service;

use App\Service\CommonService;

use Psr\Log\LoggerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;

use function PHPSTORM_META\map;

class DynamicFormService
{
	private $logger;
	private $mainFieldColumns;
	private $mainFieldColumnMappingInMeta;
	private $commonSvc;

	public function __construct(LoggerInterface $logger, CommonService $commonSvc) {
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


	public function getDataByDynamicForm(Request $request, Array $formConfigs = []): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$mainData = $this->getMainDataByDynamicForm($request, $formConfigs);
		$metaData = $this->getMetaDataByDynamicForm($request, $formConfigs);

		$results->add([
			'main_data' => $mainData,
			'meta_data' => $metaData
		]);

		return $results;
	}

	public function getMainDataByDynamicForm(Request $request, Array $formConfigs = []): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$rawRequestData = $request->request->all();

		$formConfigs = $this->getMainFormConfigsByDynamicForm($formConfigs)->toArray();

		dd($formConfigs);

		//$metaRequestData = $this->commonSvc->filterRequestProperties($request, $mainFieldNames)->request->all();
		$metaRequestData = $this->dynamicDataAdjustment($request, $formConfigs);

		//foreach ($formConfigs as $fieldIndex => $fieldConfig) {

		//	if ($fieldConfig['field_name']) switch ($fieldConfig['field_type']) {
		//		case 'step':
		//		case 'multiple':
		//			$this->getDataByDynamicForm($metaRequestData[$fieldConfig['field_name']], $fieldConfig['children']);
		//			break;

		//		case 'select':
		//		case 'autoselect':
		//		case 'autocomplete':
		//			$results->add([
		//				$fieldConfig['field_name'] => $metaRequestData[$fieldConfig['field_name']]
		//			]);
		//			break;

		//		default:
		//			$results->add([
		//				$fieldConfig['field_name'] => $metaRequestData[$fieldConfig['field_name']]
		//			]);
		//			break;
		//	}
		//}

		return $results;
	}

	public function getMetaDataByDynamicForm(Request $request, Array $formConfigs = []): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$rawRequestData = $request->request->all();

		$mainFieldNames = new ArrayCollection($this->mainFieldColumnMappingInMeta);
		$mainFieldNames = $mainFieldNames->map(function ($element) {
			return $element['request_property'];
		})->toArray();

		$metaRequestData = $this->commonSvc->removeRequestProperties($request, $mainFieldNames)->request->all();

		foreach ($formConfigs as $fieldIndex => $fieldConfig) {

			if ($fieldConfig['field_name']) switch ($fieldConfig['field_type']) {
				case 'step':
				case 'multiple':
					$this->getDataByDynamicForm($metaRequestData[$fieldConfig['field_name']], $fieldConfig['children']);
					break;

				case 'select':
				case 'autoselect':
				case 'autocomplete':
					$results->add([
						$fieldConfig['field_name'] => $metaRequestData[$fieldConfig['field_name']]
					]);
					break;

				default:
					$results->add([
						$fieldConfig['field_name'] => $metaRequestData[$fieldConfig['field_name']]
					]);
					break;
			}

		}

		return $results;
	}

	public function getMainFormConfigsByDynamicForm(Array $formConfigs): ArrayCollection
	{
		$results = new ArrayCollection($formConfigs);
		$results = $results->map(function($formConfig) {

			switch ($formConfig['field_type']) {
				case 'select':
				case 'autoselect':
				case 'autocomplete':
					$formConfig['field_name'] = $this->commonSvc->stringReplace($formConfig['field_name'], 'uuid_', 'id_');
					break;

				default:
					break;
			}

			return $formConfig;
		});

		return $results;
	}

	public function dynamicDataAdjustment(Request $request, Array $formConfigs): ArrayCollection
	{
		$results = new ArrayCollection([]);

		$requestData = $request->request->all();

		foreach ($formConfigs as $fieldIndex => $fieldConfig) {

			if ($fieldConfig->getFieldName()) switch ($fieldConfig->getFieldType()) {
				case 'step':
				case 'multiple':
					$this->getDataByDynamicForm($requestData[$fieldConfig->getFieldName()], $fieldConfig->children);
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