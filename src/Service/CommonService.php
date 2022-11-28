<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
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
use Symfony\Component\String\UnicodeString;

class CommonService {
	private $result;
	private $serializer;
	private $doctrine;
	private $doctrineManager;
	private $exprBuilder;
	private $criteria;

	public function __construct(SerializerInterface $serializer, ManagerRegistry $doctrine)
	{
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

	public function removeRequestProperties(Request $request, Array $properties): Request
	{
		$results = $request;
		foreach ($properties as $property) {
			$results->request->remove($property);
		}

		return $results;
	}

	public function filterRequestProperties(Request $request, Array $properties): Request
	{
		$data = [];
		foreach ($properties as $propertyIndex => $property) {
			$data[$property] = $request->request->get($property);
		}

		return Request::create($request->getUri(), $request->getMethod(), $data);
	}

	public function stringReplace(String $baseString, String $fromString, String $toString): String
	{
		$results = new UnicodeString($baseString);
		$results = $results->replace($fromString, $toString);
		return $results;
	}

}