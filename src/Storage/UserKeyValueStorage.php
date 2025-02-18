<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Symfony\Component\Security\Core\User\UserInterface;

class UserKeyValueStorage implements UserKeyValueStorageInterface
{
    public function __construct(
        private readonly KeyValueStorageInterface $keyValueStorage
    ) {
    }

    private function getUserCollection(UserInterface $user): string
    {
        return 'user_' . $user->getUserIdentifier();
    }

    public function has(string $key, UserInterface $user): bool
    {
        return $this->keyValueStorage->has($key . '_' . $this->getUserCollection($user));
    }

    public function get(string $key, UserInterface $user, mixed $default = null): mixed
    {
        return $this->keyValueStorage->get($key . '_' . $this->getUserCollection($user), $default);
    }

    public function getMultiple(array $keys, UserInterface $user): array
    {
        $prefixedKeys = array_map(fn($key) => $key . '_' . $this->getUserCollection($user), $keys);
        return $this->keyValueStorage->getMultiple($prefixedKeys);
    }

    public function getAll(UserInterface $user): array
    {
        $collection = $this->getUserCollection($user);
        return array_filter(
            $this->keyValueStorage->getAll(),
            fn($key) => str_starts_with($key, $collection . '_'),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function set(string $key, mixed $value, UserInterface $user): void
    {
        $this->keyValueStorage->set($key . '_' . $this->getUserCollection($user), $value);
    }

    public function setIfNotExists(string $key, mixed $value, UserInterface $user): bool
    {
        return $this->keyValueStorage->setIfNotExists($key . '_' . $this->getUserCollection($user), $value);
    }

    public function setMultiple(array $data, UserInterface $user): void
    {
        $prefixedData = array_combine(
            array_map(fn($key) => $key . '_' . $this->getUserCollection($user), array_keys($data)),
            array_values($data)
        );

        $this->keyValueStorage->setMultiple($prefixedData);
    }

    public function rename(string $key, string $newKey, UserInterface $user): void
    {
        $this->keyValueStorage->rename(
            $key . '_' . $this->getUserCollection($user),
            $newKey . '_' . $this->getUserCollection($user)
        );
    }

    public function delete(string $key, UserInterface $user): void
    {
        $this->keyValueStorage->delete($key . '_' . $this->getUserCollection($user));
    }

    public function deleteMultiple(array $keys, UserInterface $user): void
    {
        $prefixedKeys = array_map(fn($key) => $key . '_' . $this->getUserCollection($user), $keys);
        $this->keyValueStorage->deleteMultiple($prefixedKeys);
    }

    public function deleteAll(UserInterface $user): void
    {
        $collection = $this->getUserCollection($user);
        $allKeys = array_keys($this->keyValueStorage->getAll());

        $userKeys = array_filter(
            $allKeys,
            fn($key) => str_starts_with($key, $collection . '_')
        );

        $this->keyValueStorage->deleteMultiple($userKeys);
    }
}
