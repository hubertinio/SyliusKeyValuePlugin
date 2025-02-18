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

    public function findByKey(string $key): ?KeyValue
    {
        return $this->findOneBy(['key' => $key]);
    }

    public function save(KeyValue $keyValue): void
    {
        $this->_em->persist($keyValue);
        $this->_em->flush();
    }

    public function remove(KeyValue $keyValue): void
    {
        $this->_em->remove($keyValue);
        $this->_em->flush();
    }

    public function deleteAll(): void
    {
        $this->_em->createQuery('DELETE FROM Hubertinio\SyliusKeyValuePlugin\Entity\KeyValue')->execute();
    }
}
