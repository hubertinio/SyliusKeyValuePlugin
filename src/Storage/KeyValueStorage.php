<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Hubertinio\SyliusKeyValuePlugin\Entity\KeyValue;
use Hubertinio\SyliusKeyValuePlugin\Repository\KeyValueRepository;

class KeyValueStorage implements KeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueRepository $repository
    ) {
    }

    public function has(string $key): bool
    {
        return $this->repository->findByKey($key) !== null;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->findByKey($key)?->getValue() ?? $default;
    }

    public function getMultiple(array $keys): array
    {
        return array_map(fn($key) => $this->get($key), $keys);
    }

    public function getAll(): array
    {
        return array_reduce(
            $this->repository->findAll(),
            fn($result, $entry) => $result + [$entry->getKey() => $entry->getValue()],
            []
        );
    }

    public function set(string $key, mixed $value): void
    {
        $keyValue = $this->repository->findByKey($key) ?? new KeyValue($key, $value);
        $keyValue->setValue($value);
        $this->repository->save($keyValue);
    }

    public function setIfNotExists(string $key, mixed $value): bool
    {
        if ($this->has($key)) {
            return false;
        }
        $this->set($key, $value);
        return true;
    }

    public function setMultiple(array $data): void
    {
        array_walk($data, fn($value, $key) => $this->set($key, $value));
    }

    public function rename(string $key, string $newKey): void
    {
        if (!$this->has($newKey)) {
            $keyValue = $this->repository->findByKey($key);
            if ($keyValue) {
                $keyValue->setKey($newKey);
                $this->repository->save($keyValue);
            }
        }
    }

    public function delete(string $key): void
    {
        if ($keyValue = $this->repository->findByKey($key)) {
            $this->repository->remove($keyValue);
        }
    }

    public function deleteMultiple(array $keys): void
    {
        array_walk($keys, fn($key) => $this->delete($key));
    }

    public function deleteAll(): void
    {
        $this->repository->deleteAll();
    }
}
