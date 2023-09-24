<?php

namespace App\Repository;

use App\Entity\Try01;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Try01>
 *
 * @method Try01|null find($id, $lockMode = null, $lockVersion = null)
 * @method Try01|null findOneBy(array $criteria, array $orderBy = null)
 * @method Try01[]    findAll()
 * @method Try01[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Try01Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Try01::class);
    }

//    /**
//     * @return Try01[] Returns an array of Try01 objects
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

//    public function findOneBySomeField($value): ?Try01
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
