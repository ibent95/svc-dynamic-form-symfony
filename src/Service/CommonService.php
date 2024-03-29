<?php

namespace App\Service;

use Brick\Math\BigInteger;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    private Collection $response;
    private Collection $responseData;
    private int $responseStatusCode;

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
	private Collection $paginator;

	public function __construct(
		SerializerInterface $serializer,
		ManagerRegistry $doctrine,
		EntityManagerInterface $entityManager,
		SluggerInterface $slugger,
        ParameterBagInterface $parameter
	)
	{
		
        // Initial response value
        $this->responseData         = new ArrayCollection([
            'info'      	=> '',
            'message'   	=> '',
            'data'      	=> [],
        ]);
        $this->responseStatusCode   = 400;
		$this->response				= new ArrayCollection([
			'data'			=> $this->responseData,
			'status_code'	=> $this->responseStatusCode,
		]);

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
		$this->paginator	    = new ArrayCollection([
			'limit' => 0,
			'offset' => 0,
			'page_index' => 0,
		]);
	}

	public function setResponse(
		array $responseData,
		int $responseStatusCode,
		?array $responseDataAppend = null,
	) : Collection {
		$this->responseData 		= ($responseData) ? new ArrayCollection($responseData) : $this->responseData;
		$this->responseStatusCode 	= ($responseStatusCode) ? $responseStatusCode : $this->responseStatusCode;

		if ($responseData || $responseStatusCode) {
			$this->response->set('data', $this->responseData);
			$this->response->set('status_code', $this->responseStatusCode);
		}

		return $this->response;
	}

	public function setPaginator(Request $request) : Collection
	{
		$limit                      = $request->get('limit');
		$offset                     = $request->get('offset');
		$pageIndex                  = $request->get('page_index');

		if ($pageIndex) {
			$offset                 = $limit * $pageIndex; // Can also ($pageNumber -1)
		}

		$this->paginator->set('limit', $limit);
		$this->paginator->set('offset', $offset);
		$this->paginator->set('page_index', $pageIndex);

		return $this->paginator;
	}

	public function getEntityIdentifierFromUnit(object $object): Mixed
	{
		return $this->doctrineManager->getUnitOfWork()->getEntityIdentifier($object);
	}

	public function createUUIDShort() : string
	{
		/** Changed from mysql UUID_SHORT() function,
		 * to PHP arbitrary precision numbers library such as GMP BCMath based.
		 * Alternativelly, I use Brick/Math library (https://github.com/brick/math).
		 * 
		 * MySQL func: $this->doctrineManager->getConnection()->executeQuery('SELECT UUID_SHORT() AS uuid_short')->fetchOne();
		 * GMP func: (string) gmp_random_range($from, $to);
		 * Brick/Math func: BigInteger::randomRange($from, $to);
		 */ 
		$from = '0';
		$to = '92233720368547758';
		return BigInteger::randomRange($from, $to);
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
		string | NULL $secondaryUrlPart = 'api/v1/files'
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