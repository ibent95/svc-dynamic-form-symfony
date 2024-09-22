<?php

namespace App\Service;

use App\Entity\Publication;
use App\Entity\PublicationForm;
use App\Entity\PublicationFormVersion;
use App\Entity\PublicationMeta;
use App\Entity\PublicationStatus;
use App\Entity\TemporaryFileUpload;
use App\Repository\PublicationFormVersionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class PublicationFormVersionService {
    private $publicationFormVersionRepo;

	private $doctrine;
	private $doctrineManager;
	private $logger;
    private $serializer;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;
    private $results;

    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        CommonService $commonSvc,
        PublicationFormVersionRepository $publicationFormVersionRepo
    )
    {
		$this->doctrine 		= $doctrine;
		$this->doctrineManager 	= $doctrine->getManager();
		$this->logger			= $logger;
        $this->serializer       = $serializer;

        $this->exprBuilder 		= Criteria::expr();
        $this->criteria 		= new Criteria();

        // Other service`s
        $this->commonSvc 		= $commonSvc;
        $this->results          = null;

        $this->publicationFormVersionRepo = $publicationFormVersionRepo;
    }

    public function getQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        Int $maxResults = null,
        Int $firstResult = null
    ): ?array
    {
        $this->results = [
            'count' => 0,
            'data' => []
        ];

        $params = new ArrayCollection($parameters);
        $params = $params->filter(function (mixed $value, mixed $key) {
            return $value !== null;
        });

        /** @var Result $data */
        $data = $this->publicationFormVersionRepo->getRawQueryBuilderAll($params->toArray(), $orderBy, $maxResults, $firstResult);

        if ($data) {
            $this->results = [
                'count' => $this->publicationFormVersionRepo->getCount(),
                'data'  => $data->fetchAllAssociative(),
            ];
        }

        return $this->results;
    }

}