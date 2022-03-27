<?php

namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
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

	public function __construct(SerializerInterface $serializer) {
		//$this->serializer = $serializer;
	}

	public function normalizeObject($object, String $resultFormat = null): ?Array
	{
		$this->result = [];

		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$dateTimeNormalizerDefaultContext = [
			DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'
		];

		$objectNormalizerDefaultContext = [
			AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
				return $object->getName();
			},
			AbstractObjectNormalizer::ENABLE_MAX_DEPTH => false
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
}