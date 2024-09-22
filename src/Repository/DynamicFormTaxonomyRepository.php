<?php

namespace App\Repository;

use App\Entity\DynamicFormTaxonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DynamicFormTaxonomy>
 *
 * @method DynamicFormTaxonomy|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicFormTaxonomy|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicFormTaxonomy[]    findAll()
 * @method DynamicFormTaxonomy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicFormTaxonomyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicFormTaxonomy::class);
    }

//    /**
//     * @return DynamicFormTaxonomy[] Returns an array of DynamicFormTaxonomy objects
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

//    public function findOneBySomeField($value): ?DynamicFormTaxonomy
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
