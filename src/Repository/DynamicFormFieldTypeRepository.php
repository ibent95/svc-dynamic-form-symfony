<?php

namespace App\Repository;

use App\Entity\DynamicFormFieldType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DynamicFormFieldType>
 *
 * @method DynamicFormFieldType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicFormFieldType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicFormFieldType[]    findAll()
 * @method DynamicFormFieldType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicFormFieldTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicFormFieldType::class);
    }

//    /**
//     * @return DynamicFormFieldType[] Returns an array of DynamicFormFieldType objects
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

//    public function findOneBySomeField($value): ?DynamicFormFieldType
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
