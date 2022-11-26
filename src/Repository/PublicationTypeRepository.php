<?php

namespace App\Repository;

use App\Entity\PublicationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationType[]    findAll()
 * @method PublicationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationType::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PublicationType $entity, bool $flush = true): void
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
    public function remove(PublicationType $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PublicationType[] Returns an array of PublicationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByCode($code): ?PublicationType
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt. = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByCode($code): ?PublicationType
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt.publication_type_code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findActiveOneByCode($code): ?PublicationType
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt.publication_type_code = :code')
            ->andWhere('pt.flag_active = true')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
