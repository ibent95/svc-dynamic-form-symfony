<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
use Symfony\Component\Uid\Uuid;

class CommonService {
	/** @var $results Mixed */
	private $results;
	private $serializer;
	private $doctrine;
	private $doctrineManager;
	private $entityManager;
	private $exprBuilder;
	private $criteria;

	public function __construct(SerializerInterface $serializer, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
	{
		/** @var $serializer SerializeInterface */
		$this->serializer 		= $serializer;
		$this->doctrine 		= $doctrine;
		$this->doctrineManager 	= $doctrine->getManager();
		$this->entityManager 	= $entityManager;

		$this->exprBuilder 		= Criteria::expr();
		$this->criteria 		= new Criteria();

		$this->results			= [];
	}

	public function getEntityIdentifierFromUnit($object): Mixed
	{
		/** @var $results EntityManager */
		$this->results = $this->doctrineManager->getUnitOfWork()->getEntityIdentifier($object);

		return $this->results;
	}

	public function createUUIDShort() : string
	{
		/** @var $this->doctrineManager EntityManager */
		return $this->doctrineManager->getConnection()->executeQuery('SELECT UUID_SHORT() AS uuid_short')->fetchOne();
	}

	public function createUUID() : string
	{
		return Uuid::v4();
	}

	public function normalizeObject($object, String $resultFormat = null, Bool $enableMaxDepth = false): ?Array
	{
		$this->results = [];

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, null, null, null)
		];

		// $serializer = new Serializer();

		$this->results = $this->serializer->normalize($object, $resultFormat);

		return $this->results;
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