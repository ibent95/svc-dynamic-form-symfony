<?php

namespace App\Repository;

use App\Entity\PublicationForm;
use App\Entity\PublicationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Result;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationForm[]    findAll()
 * @method PublicationForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationFormRepository extends ServiceEntityRepository
{
    private Array $publicColumns;
    private $results;
    private Int $count;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationForm::class);

        $this->publicColumns = [
            "field_label",
            "field_type",
            "field_name",
            "field_id",
            "field_class",
            "field_placeholder",
            "field_options",
            "field_configs",
            "description",
            "order_position",
            "validation_configs",
            "error_message",
            "dependency_child",
            "dependency_parent",
            "flag_required",
            "flag_field_form_type",
            "flag_field_title",
            "flag_field_publish_date",
            "flag_active",
            //"create_user",
            "created_at",
            //"update_user",
            "updated_at",
            "uuid",
        ];
        $this->results = null;
        $this->count = 0;
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

    public function getCount() : Int {
        return $this->count;
    }

    public function getFormByCode(String $code)
    {
        $result = $this->_em->f;

        return $result;
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

    /**
      * @return PublicationForm[] Returns an array of PublicationForm objects
      */
    public function getQueryBuilderAll(
        Array $parameters = [],
        Array $orderBy = ['id' => 'DESC'],
        Int $maxResults = null,
        Int $firstResult = null
    ): Query
    {
        $this->results = $this->createQueryBuilder('pf');

        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $this->results = $this->results
                    ->andWhere("pf.$key = :$key")
                    ->setParameter($key, $value)
                ;
            }
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $order) {
                $this->results = $this->results->orderBy("pf.$key", $order);
            }
        }

        if ($maxResults !== NULL) {
            $this->results = $this->results->setMaxResults($maxResults);
        }

        if ($firstResult !== NULL) {
            $this->results = $this->results->setFirstResult($firstResult);
        }

        return $this->results->getQuery(); // ->getResult()
    }

    /**
      * @return PublicationForm[] Returns an array of PublicationForm objects
      */
    public function getRawQueryBuilderAll(
        Array $parameters = [],
        Array $orderBy = ['id' => 'DESC'],
        Int $maxResults = null,
        Int $firstResult = null
    ): Result
    {
        $connection = $this->_em->getConnection();

        $sql = 'SELECT ';

        $publicColumnsLastIndex = count($this->publicColumns) - 1;
        foreach ($this->publicColumns as $index => $column) {
            $sql .= "pf.$column" . (($index !== $publicColumnsLastIndex) ? ', ' : ' ' );
        }

        $sql .= 'FROM publication_form pf ';

        if ($orderBy) {
            $sql .= "ORDER BY ";
            $increment = 0;
            $lastIncrement = count(array_keys($orderBy)) - 1;
            foreach ($orderBy as $key => $order) {
                $sql .= "pf.$key $order" . (($increment !== $lastIncrement) ? ', ' : ' ');
                $increment++;
            }
        }

        $this->count = $connection->executeQuery($sql, $parameters)->rowCount();

        if ($maxResults !== NULL) {
            $sql .= "LIMIT $maxResults ";
        }

        if ($firstResult !== NULL) {
            $sql .= "OFFSET $firstResult";
        }

        return $connection->executeQuery($sql, $parameters); // ->fetchAllAssociative();
    }

    ///**
    //  * @return PublicationForm[] Returns an array of PublicationForm objects
    //  */
    //public function findByExampleField($value)
    //{
    //    return $this->createQueryBuilder('f')
    //        ->andWhere('f.exampleField = :val')
    //        ->setParameter('val', $value)
    //        ->orderBy('f.id', 'ASC')
    //        ->setMaxResults(10)
    //        ->getQuery()
    //        ->getResult()
    //    ;
    //}

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
