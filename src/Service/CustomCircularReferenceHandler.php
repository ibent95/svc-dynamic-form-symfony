<?php

namespace App\Service;

class CustomCircularReferenceHandler
{

	public function __invoke($object, $format, $context)
	{
		return $object->getId();
	}

}