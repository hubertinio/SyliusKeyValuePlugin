<?php

namespace Hubertinio\SyliusKeyValuePlugin\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Hubertinio\SyliusKeyValuePlugin\Entity\KeyValue;

class KeyValueRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeyValue::class);
    }

    /**
     * Save or update a KeyValue entity.
     */
    public function save(KeyValue $keyValue, bool $flush = true): void
    {
        $this->getEntityManager()->persist($keyValue);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a KeyValue entity.
     */
    public function remove(KeyValue $keyValue, bool $flush = true): void
    {
        $this->getEntityManager()->remove($keyValue);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find an entity by key and collection.
     */
    public function findByKeyAndCollection(string $key, ?string $collection): ?KeyValue
    {
        return $this->findOneBy([
            'key' => $key,
            'collection' => $collection,
        ]);
    }

    /**
     * Find all key-value entries by collection.
     */
    public function findByCollection(string $collection): array
    {
        return $this->findBy(['collection' => $collection]);
    }

    /**
     * Deletes all key-value entries from the table.
     */
    public function deleteAll(): void
    {
        $this->createQueryBuilder('kv')
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * Deletes all entries for a specific collection.
     */
    public function deleteByCollection(string $collection): void
    {
        $this->createQueryBuilder('kv')
            ->delete()
            ->where('kv.collection = :collection')
            ->setParameter('collection', $collection)
            ->getQuery()
            ->execute();
    }
}
