<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Psr\Cache\CacheItemPoolInterface;

class KeyValueStorageCachable implements KeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueStorageInterface $keyValueStorage,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    private function getCacheKey(string $key): string
    {
        return 'key_value_' . $key;
    }

    public function has(string $key): bool
    {
        return $this->cache->hasItem($this->getCacheKey($key)) || $this->keyValueStorage->has($key);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = $this->getCacheKey($key);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $value = $this->keyValueStorage->get($key, $default);

        if ($value !== null) {
            $cacheItem->set($value);
            $this->cache->save($cacheItem);
        }

        return $value;
    }

    public function getMultiple(array $keys): array
    {
        return $this->keyValueStorage->getMultiple($keys);
    }

    public function getAll(): array
    {
        return $this->keyValueStorage->getAll();
    }

    public function set(string $key, mixed $value): void
    {
        $this->keyValueStorage->set($key, $value);
        $this->cache->deleteItem($this->getCacheKey($key));
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
        $this->keyValueStorage->setMultiple($data);
        array_walk($data, fn($_, $key) => $this->cache->deleteItem($this->getCacheKey($key)));
    }

    public function rename(string $key, string $newKey): void
    {
        $this->keyValueStorage->rename($key, $newKey);
        $this->cache->deleteItem($this->getCacheKey($key));
        $this->cache->deleteItem($this->getCacheKey($newKey));
    }

    public function delete(string $key): void
    {
        $this->keyValueStorage->delete($key);
        $this->cache->deleteItem($this->getCacheKey($key));
    }

    public function deleteMultiple(array $keys): void
    {
        $this->keyValueStorage->deleteMultiple($keys);
        array_walk($keys, fn($key) => $this->cache->deleteItem($this->getCacheKey($key)));
    }

    public function deleteAll(): void
    {
        $this->keyValueStorage->deleteAll();
        $this->cache->clear();
    }
}
