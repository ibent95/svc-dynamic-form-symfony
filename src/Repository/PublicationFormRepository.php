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
    private array $publicColumns;
    private $results;
    private int $count;

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

    public function getCount(): int {
        return $this->count;
    }

    public function getFormByCode(string $code)
    {
        $result = $this->_em->f;

        return $result;
    }

    public function getMasterData(string $tableName, string $orderDirection = 'ASC', int $maxResult = null): ?array
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

    public function getTaxonomyTerms(string $taxonomyName, string $orderDirection = 'ASC', int $maxResult = null): ?array
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
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): Query
    {
        $this->results = $this->createQueryBuilder('pf');

        if ($parameters) {
            foreach ($parameters as $key => $value) {
                if ($value && $key == 'search_key') {
                    $formNameBindKey        = $key . '_1';
                    $formTypeBindKey        = $key . '_2';
                    $formIdBindKey          = $key . '_3';
                    $formClassBindKey       = $key . '_4';
                    $formPlaceholderBindKey = $key . '_5';
                    $formDescriptionBindKey = $key . '_6';

                    $this->results = $this->results
                        ->orWhere("pf.field_name LIKE :$formNameBindKey")
                        ->orWhere("pf.field_type LIKE :$formTypeBindKey")
                        ->orWhere("pf.field_id LIKE :$formIdBindKey")
                        ->orWhere("pf.field_class LIKE :$formClassBindKey")
                        ->orWhere("pf.field_placeholder LIKE :$formPlaceholderBindKey")
                        ->orWhere("pf.description LIKE :$formDescriptionBindKey")
                        ->setParameter($formNameBindKey, "%$value%")
                        ->setParameter($formTypeBindKey, "%$value%")
                        ->setParameter($formIdBindKey, "%$value%")
                        ->setParameter($formClassBindKey, "%$value%")
                        ->setParameter($formPlaceholderBindKey, "%$value%")
                        ->setParameter($formDescriptionBindKey, "%$value%");
                } else {
                    $this->results = $this->results
                        ->andWhere("pf.$key = :$key")
                        ->setParameter($key, $value)
                    ;
                }

            }
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $order) {
                $this->results = $this->results->orderBy("pf.$key", $order);
            }
        }

        $this->count = count($this->results->getQuery()->getArrayResult());

        if ($maxResults !== null) {
            $this->results = $this->results->setMaxResults($maxResults);
        }

        if ($firstResult !== null) {
            $this->results = $this->results->setFirstResult($firstResult);
        }

        return $this->results->getQuery(); // ->getResult()
    }

    /**
      * @return PublicationForm[] Returns an array of PublicationForm objects
      */
    public function getRawQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): Result
    {
        $connection = $this->_em->getConnection();

        $sql = 'SELECT ';

        $publicColumnsLastIndex = count($this->publicColumns) - 1;
        foreach ($this->publicColumns as $index => $column) {
            $sql .= "pf.$column" . (($index !== $publicColumnsLastIndex) ? ', ' : ' ' );
        }

        $sql .= 'FROM publication_form pf ';

        // Prepare parameters before set bindings
        if ($parameters) {
            $sql .= 'WHERE ';

            $parametersInc = 0;
            $parametersLength = count($parameters) - 1;

            foreach ($parameters as $key => $value) {

                if ($value && $key == 'search_key') {
                    $formNameBindKey        = $key . '_1';
                    $formTypeBindKey        = $key . '_2';
                    $formIdBindKey          = $key . '_3';
                    $formClassBindKey       = $key . '_4';
                    $formPlaceholderBindKey = $key . '_5';
                    $formDescriptionBindKey = $key . '_6';

                    $sql .= "(LOWER(pf.field_name) LIKE :$formNameBindKey OR LOWER(pf.field_type) LIKE :$formTypeBindKey OR LOWER(pf.field_id) LIKE :$formIdBindKey OR LOWER(pf.field_class) LIKE :$formClassBindKey OR LOWER(pf.field_placeholder) LIKE :$formPlaceholderBindKey OR LOWER(pf.description) LIKE :$formDescriptionBindKey) ";
                } else {
                    if ($value) $sql .= "pf.$key = :$key ";
                }

                if ($value && $parametersInc != $parametersLength) {
                    $sql .= 'AND ';
                }

                $parametersInc++;
            }
        }

        // Ordering data
        if ($orderBy) {
            $sql .= "ORDER BY ";
            $increment = 0;
            $lastIncrement = count(array_keys($orderBy)) - 1;
            foreach ($orderBy as $key => $order) {
                $sql .= "pf.$key $order" . (($increment !== $lastIncrement) ? ', ' : ' ');
                $increment++;
            }
        }

        $preparedStatement = $connection->prepare($sql);

        $this->count = $this->countRowFromNativeSql($sql, $parameters);

        if ($maxResults !== null) {
            $sql .= "LIMIT $maxResults ";
        }

        if ($firstResult !== null) {
            $sql .= "OFFSET $firstResult";
        }

        $preparedStatement = $connection->prepare($sql);

        // Set bindings after prepare parameters
        if ($parameters) {
            foreach ($parameters as $key => &$value) {

                if ($value && $key == 'search_key') {
                    $value = strtolower($value);
                    $preparedStatement->bindValue($key . '_1', "%$value%");
                    $preparedStatement->bindValue($key . '_2', "%$value%");
                    $preparedStatement->bindValue($key . '_3', "%$value%");
                    $preparedStatement->bindValue($key . '_4', "%$value%");
                    $preparedStatement->bindValue($key . '_5', "%$value%");
                    $preparedStatement->bindValue($key . '_6', "%$value%");
                } else {
                    if ($value) $preparedStatement->bindValue($key, $value);
                }

            }
        }

        return $preparedStatement->executeQuery(); // ->fetchAllAssociative();
    }

    private function countRowFromNativeSql(string $sql, array $parameters = []): int {
        $connection = $this->_em->getConnection();

        $preparedStatement = $connection->prepare($sql);

        // Set bindings after prepare parameters
        if ($parameters) {
            foreach ($parameters as $key => &$value) {

                if ($value && $key == 'search_key') {
                    $value = strtolower($value);
                    $preparedStatement->bindValue($key . '_1', "%$value%");
                    $preparedStatement->bindValue($key . '_2', "%$value%");
                    $preparedStatement->bindValue($key . '_3', "%$value%");
                    $preparedStatement->bindValue($key . '_4', "%$value%");
                    $preparedStatement->bindValue($key . '_5', "%$value%");
                    $preparedStatement->bindValue($key . '_6', "%$value%");
                } else {
                    if ($value) $preparedStatement->bindValue($key, $value);
                }

            }
        }

        return $preparedStatement->executeQuery()->rowCount();
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
