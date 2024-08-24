<?php

namespace App\Repository;

use App\Entity\DynamicFormFieldOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DynamicFormFieldOptions>
 *
 * @method DynamicFormFieldOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicFormFieldOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicFormFieldOptions[]    findAll()
 * @method DynamicFormFieldOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicFormFieldOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicFormFieldOptions::class);
    }

//    /**
//     * @return DynamicFormFieldOptions[] Returns an array of DynamicFormFieldOptions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DynamicFormFieldOptions
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
