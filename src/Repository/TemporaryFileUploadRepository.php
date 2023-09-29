<?php

namespace App\Repository;

use App\Entity\TemporaryFileUpload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TemporaryFileUpload>
 *
 * @method TemporaryFileUpload|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemporaryFileUpload|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemporaryFileUpload[]    findAll()
 * @method TemporaryFileUpload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemporaryFileUploadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemporaryFileUpload::class);
    }

//    /**
//     * @return TemporaryFileUpload[] Returns an array of TemporaryFileUpload objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TemporaryFileUpload
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
