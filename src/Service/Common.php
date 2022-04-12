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

	function setFields(array $flatArrays)
	{
		$recursive = $this->setRecursiveFields('id', 'form_parent_id', $flatArrays);
		$data = $this->cleanFields(["id", "form_version_id", "form_parent_id", "order", "flag_judul_publikasi", "flag_tgl_publikasi", "flag_peran"], $recursive);
		return $data;
	}

	function setRecursiveFields($idField, $idParentField, array &$elements = [], Int $parentId = NULL)
	{
		$branch = [];
		foreach ($elements as $element) {
			if ($element[$idParentField] == $parentId) {
				$children = $this->setRecursiveFields($idField, $idParentField, $elements, $element[$idField]);

				if ($element['dependency_parent'] || $element['dependency_child']) {
					$dependencyParent = json_decode($element['dependency_parent']);
					$dependencyChild = json_decode($element['dependency_child']);

					$element['dependency_parent'] = (is_array($dependencyParent)) ? $dependencyParent : $element['dependency_parent'];
					$element['dependency_child'] = (is_array($dependencyChild)) ? $dependencyChild : $element['dependency_child'];
				}

				$element['children'] = ($children) ? $children : [];
				if ($element['field_type'] === 'multiple') {
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
						"field_label" => NULL, "field_id" => NULL,
						"field_type" => "hidden", "field_class" => NULL,
						"field_name" => "uuid_" . $element['field_name'],
						"field_placeholder" => NULL, "field_options" => NULL,
						"default_value" => NULL, "validation_config" => NULL,
						"tabel" => "publication_meta", "flag_multiple_field" => 0,
						"order" => 1, "uuid_form" => NULL,
						"children" => []
					];
				}

				// Expression builder for get multiple field in children data
				$this->criteria->where($this->exprBuilder->eq('field_type', 'multiple'));

				// Search multiple field in children data
				$childrenCollection 			= new ArrayCollection($children);
				$childrenCollectionMatch 		= $childrenCollection->matching($this->criteria);

				// Define flag_multiple_field set it`s value to true if multiple field found in children data
				$element['flag_multiple_field'] = ($element['field_type'] === 'wizard' && count($children) > 0 && $childrenCollectionMatch->count() > 0) ? TRUE : FALSE ;

				// Handling or setting grid configuration

				$branch[] = $element;
				unset($elements[$element[$idField]]);
			}
		}
		return $branch;
	}

	function cleanFields(array $acceptableFields, array &$elements = [])
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

}