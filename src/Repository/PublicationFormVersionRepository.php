<?php

namespace App\Repository;

use App\Entity\PublicationFormVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationFormVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationFormVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationFormVersion[]    findAll()
 * @method PublicationFormVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationFormVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationFormVersion::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PublicationFormVersion $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PublicationFormVersion $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findFormVersionWithForm(Int $publicationFormVersionId, String $publicationFormVersionCode = NULL): ?Array
    {
        return $this->createQueryBuilder('fv')
            ->andWhere('fv.id = :id')
            ->setParameter('id', $publicationFormVersionId)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY)
        ;
    }

    /*
    public function findOneBySomeField($value): ?PublicationFormVersion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('fv.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
