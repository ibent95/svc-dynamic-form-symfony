<?php

namespace App\Repository;

use App\Entity\PublicationFormVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Result;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PublicationFormVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationFormVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationFormVersion[]    findAll()
 * @method PublicationFormVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationFormVersionRepository extends ServiceEntityRepository
{
    private array $publicColumns;
    private $results;
    private Int $count;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationFormVersion::class);

        $this->publicColumns = [
            "publication_form_version_name",
            "publication_form_version_code",
            "grid_system",
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
    public function add(PublicationFormVersion $entity, bool $flush = true): void
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
    public function remove(PublicationFormVersion $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getCount(): Int
    {
        return $this->count;
    }

    public function findFormVersionWithForm(int $publicationFormVersionId, string $publicationFormVersionCode = null): ?array
    {
        return $this->createQueryBuilder('fv')
            ->andWhere('fv.id = :id')
            ->setParameter('id', $publicationFormVersionId)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY)
        ;
    }

    /**
     * @return PublicationFormVersion[] Returns an array of PublicationFormVersion objects
     */
    public function getQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): Query {
        $this->results = $this->createQueryBuilder('pfv');

        if ($parameters) {
            foreach ($parameters as $key => $value) {
                $this->results = $this->results
                    ->andWhere("pfv.$key = :$key")
                    ->setParameter($key, $value);
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
     * @return PublicationFormVersion[] Returns an array of PublicationFormVersion objects
     */
    public function getRawQueryBuilderAll(
        array $parameters = [],
        array $orderBy = ['id' => 'DESC'],
        int $maxResults = null,
        int $firstResult = null
    ): Result {
        $connection = $this->_em->getConnection();

        $sql = 'SELECT ';

        $publicColumnsLastIndex = count($this->publicColumns) - 1;
        foreach ($this->publicColumns as $index => $column) {
            $sql .= "pfv.$column" . (($index !== $publicColumnsLastIndex) ? ', ' : ' ');
        }

        $sql .= 'FROM publication_form_version pfv ';

        // Prepare parameters before set bindings
        if ($parameters) {
            $sql .= 'WHERE ';

            $parametersInc = 0;
            $parametersLength = count($parameters) - 1;

            foreach ($parameters as $key => $value) {

                if ($key == 'search_key') {
                    $formVersionNameBindKey = $key . '_1';
                    $formVersionCodeBindKey = $key . '_2';

                    $sql .= "(LOWER(pfv.publication_form_version_name) LIKE :$formVersionNameBindKey OR LOWER(pfv.publication_form_version_code) LIKE :$formVersionCodeBindKey) ";
                } else {
                    $sql .= "pfv.$key = :$key ";
                }

                if ($parametersInc != $parametersLength) {
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
                $sql .= "pfv.$key $order" . (($increment !== $lastIncrement) ? ', ' : ' ');
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

                if ($key == 'search_key') {
                    $value = strtolower($value);
                    $preparedStatement->bindValue($key . '_1', "%$value%");
                    $preparedStatement->bindValue($key . '_2', "%$value%");
                } else {
                    $preparedStatement->bindValue($key, $value);
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

                if ($key == 'search_key') {
                    $value = strtolower($value);
                    $preparedStatement->bindValue($key . '_1', "%$value%");
                    $preparedStatement->bindValue($key . '_2', "%$value%");
                } else {
                    $preparedStatement->bindValue($key, $value);
                }

            }
        }

        return $preparedStatement->executeQuery()->rowCount();
    }

    /*
    public function findOneBySomeField($value): ?PublicationFormVersion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('fv.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
