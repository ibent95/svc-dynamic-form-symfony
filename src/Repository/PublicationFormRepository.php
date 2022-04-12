<?php

namespace App\Repository;

use App\Entity\PublicationForm;
use App\Entity\PublicationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationForm[]    findAll()
 * @method PublicationForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationForm::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PublicationForm $entity, bool $flush = true): void
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
    public function remove(PublicationForm $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getMasterData(String $tableName, String $orderDirection = 'ASC', Int $maxResult = NULL): ?Array
    {
        // Initiate result variable
        $result = [];

        // Initiate query builder
        $result = $this->_em->createQueryBuilder();

        // Proccess to get data for specifict table
        switch ($tableName) {

            // For publication`s status
            case 'publication_status':
                $result = $result->select(
                    $tableName . '.' . $tableName . '_name',
                    $tableName . '.' . $tableName . '_name as value',
                    $tableName . '.uuid',
                )->from(PublicationStatus::class, $tableName);
                break;

            //default:
            //    $result = $result->select(
            //        $tableName . '.' . $tableName . '_name',
            //        $tableName . '.' . $tableName . '_name as value',
            //        $tableName . '.uuid',
            //    );
            //    break;

        }

        //$result = $result->andWhere($tableName . '.exampleField = :val')->setParameter('val', $tableName);
        $result = $result->orderBy('value', $orderDirection);

        // Set max results if $maxresult is set
        if ($maxResult) $result = $result->setMaxResults(10);

        // get query result
        $result = $result->getQuery()->getArrayResult();

        // return result
        return $result;
    }

    public function getTaxonomyTerms(String $taxonomyName, String $orderDirection = 'ASC', Int $maxResult = NULL): ?Array
    {
        // Initiate result variable
        $result = [];

        // Initiate query builder
        $result = $this->_em->createQueryBuilder();

        // Proccess to get data for specifict table
        switch ($taxonomyName) {

            // For publication`s status
            case 'publication_status':
                $result = $result->select(
                    $taxonomyName . '.' . $taxonomyName . '_name',
                    $taxonomyName . '.' . $taxonomyName . '_name as value',
                    $taxonomyName . '.uuid',
                )->from(PublicationStatus::class, $taxonomyName);
                break;

            //default:
            //    $result = $result->select(
            //        $taxonomyName . '.' . $taxonomyName . '_name',
            //        $taxonomyName . '.' . $taxonomyName . '_name as value',
            //        $taxonomyName . '.uuid',
            //    );
            //    break;

        }

        //$result = $result->andWhere($taxonomyName . '.exampleField = :val')->setParameter('val', $taxonomyName);
        $result = $result->orderBy('value', $orderDirection);

        // Set max results if $maxresult is set
        if ($maxResult) $result = $result->setMaxResults(10);

        // get query result
        $result = $result->getQuery()->getArrayResult();

        // return result
        return $result;
    }

    // /**
    //  * @return PublicationForm[] Returns an array of PublicationForm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PublicationForm
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
