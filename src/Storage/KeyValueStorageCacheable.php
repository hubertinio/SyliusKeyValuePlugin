<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Hubertinio\SyliusKeyValuePlugin\Helper\CacheKeyGeneratorInterface;
use Psr\Cache\CacheItemPoolInterface;
use Webmozart\Assert\Assert;

class KeyValueStorageCacheable implements KeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueStorageInterface $keyValueStorage,
        private readonly CacheKeyGeneratorInterface $cacheKeyGenerator,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    private function getCacheKey(string $key, ?string $collection = null): string
    {
        Assert::nullOrScalar($collection);

        return $this->cacheKeyGenerator::generate('key_value', $key, ['collection' => $collection]);
    }

    private function getCollectionCacheKey(?string $collection): string
    {
        Assert::nullOrScalar($collection);

        return $this->cacheKeyGenerator::generate('key_value_collection', $collection ?? 'default');
    }

    public function has(string $key, ?string $collection = null): bool
    {
        return $this->cache->hasItem($this->getCacheKey($key, $collection))
            || $this->keyValueStorage->has($key, $collection);
    }

    public function get(string $key, mixed $default = null, ?string $collection = null): mixed
    {
        $cacheKey = $this->getCacheKey($key, $collection);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $value = $this->keyValueStorage->get($key, $default, $collection);

        if ($value !== null) {
            $cacheItem->set($value);
            $this->cache->save($cacheItem);
        }

        return $value;
    }

    public function getMultiple(array $keys, ?string $collection = null): array
    {
        return $this->keyValueStorage->getMultiple($keys, $collection);
    }

    public function getAll(?string $collection = null): array
    {
        $cacheKey = $this->getCollectionCacheKey($collection);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $values = $this->keyValueStorage->getAll($collection);

        if (!empty($values)) {
            $cacheItem->set($values);
            $this->cache->save($cacheItem);
        }

        return $values;
    }

    public function set(string $key, mixed $value, ?string $collection = null): void
    {
        $this->keyValueStorage->set($key, $value, $collection);
        $this->invalidateCache($key, $collection);
    }

    public function setIfNotExists(string $key, mixed $value, ?string $collection = null): bool
    {
        if ($this->has($key, $collection)) {
            return false;
        }

        $this->set($key, $value, $collection);
        return true;
    }

    public function setMultiple(array $data, ?string $collection = null): void
    {
        $this->keyValueStorage->setMultiple($data, $collection);
        array_walk($data, fn($_, $key) => $this->invalidateCache($key, $collection));
    }

    public function rename(string $key, string $newKey, ?string $collection = null): bool
    {
        $output = $this->keyValueStorage->rename($key, $newKey, $collection);
        $this->invalidateCache($key, $collection);
        $this->invalidateCache($newKey, $collection);

        return $output;
    }

    public function delete(string $key, ?string $collection = null): void
    {
        $this->keyValueStorage->delete($key, $collection);
        $this->invalidateCache($key, $collection);
    }

    public function deleteMultiple(array $keys, ?string $collection = null): void
    {
        $this->keyValueStorage->deleteMultiple($keys, $collection);
        array_walk($keys, fn($key) => $this->invalidateCache($key, $collection));
    }

    public function deleteAll(?string $collection = null): void
    {
        $this->keyValueStorage->deleteAll($collection);
        if ($collection) {
            $this->cache->deleteItem($this->getCollectionCacheKey($collection));
        } else {
            $this->cache->clear();
        }
    }

    private function invalidateCache(string $key, ?string $collection): void
    {
        $this->cache->deleteItem($this->getCacheKey($key, $collection));

        if ($collection) {
            $this->cache->deleteItem($this->getCollectionCacheKey($collection));
        }
    }
}
