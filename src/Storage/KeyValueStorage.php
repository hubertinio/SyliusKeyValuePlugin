<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Hubertinio\SyliusKeyValuePlugin\Entity\KeyValue;
use Hubertinio\SyliusKeyValuePlugin\Repository\KeyValueRepository;
use Webmozart\Assert\Assert;

class KeyValueStorage implements KeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueRepository $repository
    ) {
    }

    public function has(string $key, ?string $collection = null): bool
    {
        Assert::nullOrScalar($collection);

        return $this->repository->findByKeyAndCollection($key, $collection) !== null;
    }

    public function get(string $key, mixed $default = null, ?string $collection = null): mixed
    {
        Assert::nullOrScalar($collection);

        return $this->repository->findByKeyAndCollection($key, $collection)?->getValue() ?? $default;
    }

    public function getMultiple(array $keys, ?string $collection = null): array
    {
        Assert::allScalar($keys);
        Assert::nullOrScalar($collection);

        return array_reduce(
            $keys,
            fn ($result, $key) => $result + [$key => $this->get($key, null, $collection)],
            []
        );
    }

    public function getAll(?string $collection = null): array
    {
        Assert::nullOrScalar($collection);

        $entries = $collection
            ? $this->repository->findByCollection($collection)
            : $this->repository->findAll();

        return array_reduce(
            $entries,
            fn ($result, $entry) => $result + [$entry->getKey() => $entry->getValue()],
            []
        );
    }

    public function set(string $key, mixed $value, ?string $collection = null): void
    {
        try {
            Assert::nullOrScalar($collection);
            $keyValue = $this->repository->findByKeyAndCollection($key, $collection);
            if (!$keyValue) {
                $keyValue = new KeyValue($key, $value, $collection);
            }
            $keyValue->setValue($value);
            $this->repository->save($keyValue);
        } catch (\Exception $e) {
            $this;
        }
    }

    public function setIfNotExists(string $key, mixed $value, ?string $collection = null): bool
    {
        Assert::nullOrScalar($collection);

        if ($this->has($key, $collection)) {
            return false;
        }

        $this->set($key, $value, $collection);
        return true;
    }

    public function setMultiple(array $data, ?string $collection = null): void
    {
        Assert::nullOrScalar($collection);

        foreach ($data as $key => $value) {
            Assert::scalar($key);
            $this->set($key, $value, $collection);
        }
    }

    public function rename(string $key, string $newKey, ?string $collection = null): bool
    {
        Assert::nullOrScalar($collection);

        if ($this->has($newKey, $collection)) {
            return false;
        }

        $keyValue = $this->repository->findByKeyAndCollection($key, $collection);

        if ($keyValue) {
            $keyValue->setKey($newKey);
            $this->repository->save($keyValue);

            return true;
        }

        return false;
    }

    public function delete(string $key, ?string $collection = null): void
    {
        Assert::nullOrScalar($collection);
        $keyValue = $this->repository->findByKeyAndCollection($key, $collection);

        if ($keyValue) {
            $this->repository->remove($keyValue);
        }
    }

    public function deleteMultiple(array $keys, ?string $collection = null): void
    {
        Assert::allScalar($keys);
        Assert::nullOrScalar($collection);

        foreach ($keys as $key) {
            $this->delete($key, $collection);
        }
    }

    public function deleteAll(?string $collection = null): void
    {
        Assert::nullOrScalar($collection);

        if ($collection) {
            $this->repository->deleteByCollection($collection);
        }

        $this->repository->deleteAll();
    }
}
