<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserKeyValueStorageInterface
{
    /**
     * Returns the stored value for a given key.
     */
    public function get(string $key, UserInterface $user, mixed $default = NULL): mixed;

    /**
     * Saves a value for a given key.
     */
    public function set(string $key, mixed $value, UserInterface $user): void;

    /**
     * Deletes an item from the key/value store.
     */
    public function delete(string $key, UserInterface $user): void;

    /**
     * Returns all stored key/value pairs in the collection.
     */
    public function getAll(UserInterface $user): array;

    /**
     * Returns whether a given key exists in the store.
     */
    public function has(string $key, UserInterface $user): bool;

    /**
     * Returns the stored key/value pairs for a given set of keys.
     */
    public function getMultiple(array $keys, UserInterface $user): array;

    /**
     * Saves a value for a given key if it does not exist yet.
     */
    public function setIfNotExists(string $key, mixed $value, UserInterface $user): bool;

    /**
     * Saves key/value pairs.
     */
    public function setMultiple(array $data, UserInterface $user): void;

    /**
     * Renames a key.
     */
    public function rename(string $key, string $newKey, UserInterface $user);

    /**
     * Deletes multiple items from the key/value store.
     */
    public function deleteMultiple(array $keys, UserInterface $user): void;

    /**
     * Deletes all items from the key/value store.
     */
    public function deleteAll(UserInterface $user): void;
}
