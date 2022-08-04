<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Common {
	private $result;
	private $serializer;
	private $doctrine;
	private $doctrineManager;
	private $exprBuilder;
	private $criteria;

	public function __construct(SerializerInterface $serializer, ManagerRegistry $doctrine) {
		//$this->serializer = $serializer;
		$this->doctrine 		= $doctrine;
		$this->doctrineManager 	= $doctrine->getManager();

		$this->exprBuilder 		= Criteria::expr();
		$this->criteria 		= new Criteria();
	}

	public function getEntityIdentifierFromUnit($object): ?Array
	{
		$result = [];
		$result = $this->doctrineManager->getUnitOfWork()->getEntityIdentifier($object);

		return $result;
	}

	public function normalizeObject($object, String $resultFormat = null, Bool $enableMaxDepth = FALSE): ?Array
	{
		$this->result = [];
		$result = [];

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
				return $object->getName();
			},
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $objectNormalizerDefaultContext)
		];
		//$encoders = [new XmlEncoder(), new JsonEncoder()];

		$serializer = new Serializer($normalizers);

		$result = $serializer->normalize($object, $resultFormat);

		return $result;
	}

	public function serializeObject($object, String $resultFormat = null, Bool $enableMaxDepth = FALSE): ?String
	{
		$result = NULL;

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
				return $object->getName();
			},
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $objectNormalizerDefaultContext)
		];
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$serializer = new Serializer($normalizers, $encoders);

		$result = $serializer->serialize($object, $resultFormat);

		return $result;
	}

	public function deserializeObject($object, String $entityClassName, String $resultFormat = null, Bool $enableMaxDepth = FALSE): ?String
	{
		$result = NULL;

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
				return $object->getName();
			},
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null, $objectNormalizerDefaultContext)
		];
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$serializer = new Serializer($normalizers, $encoders);

		$result = $serializer->deserialize($object, $entityClassName, $resultFormat);

		return $result;
	}

	/**
	 * Function to set grid system, organize array recursive data from flat array data, also clean the array recursive data
	 */
	function setFields(Array $flatArray, Array $gridSystem = NULL): Array
	{

		// Set the array from flat to recursive
		$recursiveData = $this->setRecursiveFields('id', 'form_parent_id', $flatArray, NULL, $gridSystem);

		// Clean property of recursive data
		$data = $this->cleanFields(["id", "form_version_id", "form_parent_id", "order", "flag_judul_publikasi", "flag_tgl_publikasi", "flag_peran"], $recursiveData);

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
			$element['field_configs'] = $this->setGridConfig($element, $gridSystem) ;

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
					$dependencyChild				= json_decode($element['dependency_child'])	;

					$element['dependency_parent']	= (is_array($dependencyParent)) ? $dependencyParent : $element['dependency_parent'] ;
					$element['dependency_child']	= (is_array($dependencyChild))	? $dependencyChild	: $element['dependency_child'] ;
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
				$element['flag_multiple_field'] = (($element['field_type'] == 'wizard' || $element['field_type'] == 'stepper') && (count($children) > 0) && ($childrenCount > 0)) ? TRUE : FALSE ;

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
		$result = ($element && $elementGridConfig) ? array_merge($element['field_configs'] ?: [], $elementGridConfig ?: []) : $element['field_configs'] ;

		return $result;
	}

}