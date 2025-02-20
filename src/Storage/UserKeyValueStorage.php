<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Hubertinio\SyliusKeyValuePlugin\Helper\CacheKeyGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserKeyValueStorage implements UserKeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueStorageInterface $keyValueStorage,
        private readonly CacheKeyGeneratorInterface $cacheKeyGenerator
    ) {
    }

    private function getUserCollection(UserInterface $user): string
    {
        return $this->cacheKeyGenerator->generate(
            'user',
            $user->getUserIdentifier(),
            ['class' => get_class($this)]
        );
    }

    public function has(string $key, UserInterface $user): bool
    {
        return $this->keyValueStorage->has($key, $this->getUserCollection($user));
    }

    public function get(string $key, UserInterface $user, mixed $default = null): mixed
    {
        return $this->keyValueStorage->get($key, $default, $this->getUserCollection($user));
    }

    public function getMultiple(array $keys, UserInterface $user): array
    {
        return $this->keyValueStorage->getMultiple($keys, $this->getUserCollection($user));
    }

    public function getAll(UserInterface $user): array
    {
        return $this->keyValueStorage->getAll($this->getUserCollection($user));
    }

    public function set(string $key, mixed $value, UserInterface $user): void
    {
        $this->keyValueStorage->set($key, $value, $this->getUserCollection($user));
    }

    public function setIfNotExists(string $key, mixed $value, UserInterface $user): bool
    {
        return $this->keyValueStorage->setIfNotExists($key, $value, $this->getUserCollection($user));
    }

    public function setMultiple(array $data, UserInterface $user): void
    {
        $this->keyValueStorage->setMultiple($data, $this->getUserCollection($user));
    }

    public function rename(string $key, string $newKey, UserInterface $user): void
    {
        $this->keyValueStorage->rename($key, $newKey, $this->getUserCollection($user));
    }

    public function delete(string $key, UserInterface $user): void
    {
        $this->keyValueStorage->delete($key, $this->getUserCollection($user));
    }

    public function deleteMultiple(array $keys, UserInterface $user): void
    {
        $this->keyValueStorage->deleteMultiple($keys, $this->getUserCollection($user));
    }

    public function deleteAll(UserInterface $user): void
    {
        $this->keyValueStorage->deleteAll($this->getUserCollection($user));
    }
}
