<?php

namespace App\Repository;

use App\Entity\DynamicFormTaxonomyTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DynamicFormTaxonomyTerm>
 *
 * @method DynamicFormTaxonomyTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicFormTaxonomyTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicFormTaxonomyTerm[]    findAll()
 * @method DynamicFormTaxonomyTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicFormTaxonomyTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicFormTaxonomyTerm::class);
    }

//    /**
//     * @return DynamicFormTaxonomyTerm[] Returns an array of DynamicFormTaxonomyTerm objects
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

//    public function findOneBySomeField($value): ?DynamicFormTaxonomyTerm
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
