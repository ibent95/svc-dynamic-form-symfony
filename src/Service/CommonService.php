<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
use Symfony\Component\String\Slugger\SluggerInterface;
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
	private $dateTimeFormat;
	private $slugger;
    private $parameter;

	public function __construct(
		SerializerInterface $serializer,
		ManagerRegistry $doctrine,
		EntityManagerInterface $entityManager,
		SluggerInterface $slugger,
        ParameterBagInterface $parameter
	)
	{
		$this->results			= [];
		/** @var $serializer SerializeInterface */
		$this->serializer 		= $serializer;
		$this->doctrine 		= $doctrine;
		/** @var $doctrineManager ObjectManager */
		$this->doctrineManager 	= $doctrine->getManager();
		$this->entityManager 	= $entityManager;

		$this->exprBuilder 		= Criteria::expr();
		$this->criteria 		= new Criteria();
		$this->dateTimeFormat	= 'Y-m-d H:i:s';

		$this->slugger 			= $slugger;
        $this->parameter        = $parameter;
	}

	public function getEntityIdentifierFromUnit(object $object): Mixed
	{
		return $this->doctrineManager->getUnitOfWork()->getEntityIdentifier($object);
	}

	public function createUUIDShort() : string
	{
		// return $this->doctrineManager->getConnection()->executeQuery('SELECT UUID_SHORT() AS uuid_short')->fetchOne();
		$from = 0;
		$to = '9223372036854775807';
		return (string) gmp_random_range($from, $to);
	}

	public function createUUID() : string
	{
		return Uuid::v4();
	}

	public function normalizeObject($object, string $resultFormat = null, bool $enableMaxDepth = false): ?array
	{
		$this->results = null;

		$classMetadataFactory = new ClassMetadataFactory(
			new AnnotationLoader(
				new AnnotationReader()
			)
		);

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => $this->dateTimeFormat
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractNormalizer::IGNORED_ATTRIBUTES => [
				'lazyObjectState',
				'lazyObjectInitialized',
				'lazyObjectAsInitialized'
			],
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth,
			AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => false,
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer(
				$classMetadataFactory,
				new CamelCaseToSnakeCaseNameConverter(),
				null, null, null, null,
				$objectNormalizerDefaultContext
			)
		];

		$this->serializer = new Serializer($normalizers);

		$this->results = $this->serializer->normalize($object, $resultFormat);

		return $this->results;
	}

	public function serializeObject(
		$object,
		string $resultFormat = null,
		bool $enableMaxDepth = false
	): ?string
	{
		$this->results = null;

		$classMetadataFactory = new ClassMetadataFactory(
			new AnnotationLoader(
				new AnnotationReader()
			)
		);

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => $this->dateTimeFormat
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
				return $object->getId();
			},
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer(
				$classMetadataFactory,
				new CamelCaseToSnakeCaseNameConverter(),
				null, null, null, null,
				$objectNormalizerDefaultContext
			)
		];
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$this->serializer = new Serializer($normalizers, $encoders);

		$this->results = $this->serializer->serialize($object, $resultFormat);

		return $this->results;
	}

	public function deserializeObject(
		$object,
		string $entityClassName,
		string $resultFormat = null,
		bool $enableMaxDepth = false
	): ?string
	{
		$this->results = null;

		$classMetadataFactory = new ClassMetadataFactory(
			new AnnotationLoader(
				new AnnotationReader()
			)
		);

		$dateTimeNormalizerDefaultContext = [
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			DateTimeNormalizer::FORMAT_KEY => $this->dateTimeFormat
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
				return $object->getId();
			},
			AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $enableMaxDepth
		];

		$normalizers = [
			new DateTimeNormalizer($dateTimeNormalizerDefaultContext),
			new ObjectNormalizer(
				$classMetadataFactory,
				new CamelCaseToSnakeCaseNameConverter(),
				null, null, null, null,
				$objectNormalizerDefaultContext
			)
		];
		$encoders = [new XmlEncoder(), new JsonEncoder()];

		$this->serializer = new Serializer($normalizers, $encoders);

		$this->results = $this->serializer->	deserialize($object, $entityClassName, $resultFormat);

		return $this->results;
	}

	public function removeRequestProperties(Request $request, array $properties): Request
	{
		$results = $request;
		foreach ($properties as $property) {
			$results->request->remove($property);
		}

		return $results;
	}

	public function filterRequestProperties(Request $request, array $properties): Request
	{
		$data = [];
		foreach ($properties as $propertyIndex => $property) {
			$data[$property] = $request->request->get($property);
		}

		return Request::create($request->getUri(), $request->getMethod(), $data);
	}

	public function stringReplace(String $baseString, String $fromString, String $toString): string
	{
		$unicode = new UnicodeString($baseString);
		return $unicode->replace($fromString, $toString);
	}

	public function uploadFile(
		UploadedFile $file,
		string $parameter,
		string | NULL $secondaryUrlPart = 'public/api/v1/files'
	) : array
	{
		$originalName	= pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slugName		= $this->slugger->slug($originalName);
		$extension		= '.' . $file->guessExtension();
        $name			= $slugName . '-' . uniqid() . $extension;
		$path			= $this->parameter->get($parameter) . '/' . $name;

		$file->move($this->parameter->get('secret_' . $parameter), $name);

		return [
			'original_name' => $originalName . $extension,
			'slug' => $slugName,
			'name' => $name,
			'path' => $path,
			'url' => $this->parameter->get('app.base_url') . '/' . $secondaryUrlPart . '/' . $name,
			'extension' => $extension
		];
	}

}