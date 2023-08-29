<?php

namespace App\Service;

use App\Entity\PublicationFormVersion;
use App\Entity\PublicationType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class PublicationService {
    private $result;
    private $serializer;
    private $exprBuilder;
    private $criteria;
    private $commonSvc;

    public function __construct(SerializerInterface $serializer, CommonService $commonSvc)
    {
        $this->serializer = $serializer;

        $this->exprBuilder 		= Criteria::expr();
        $this->criteria 		= new Criteria();

        // Other service`s
        $this->commonSvc 		= $commonSvc;
    }

    public function getOneFormVersionData(PersistentCollection $sourceData, $otherData = null): PublicationFormVersion
    {
        $data = [];
        // Criteria or Query
        $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );

        if ($otherData) $this->criteria->orWhere($this->exprBuilder->eq('id', $otherData->getId()));

        // FormVersion data
        $data = $sourceData->matching($this->criteria)->first();

        return $data;
    }

    public function getAllFormMetaData(PersistentCollection $sourceData, $otherData = null): Collection
    {
        $data = [];

        $this->criteria = $this->criteria->where(
            $this->exprBuilder->eq('flag_active', true)
        );

        $data = $sourceData->matching($this->criteria);
        dd('Hihih', $data);
        return $data;
    }

}