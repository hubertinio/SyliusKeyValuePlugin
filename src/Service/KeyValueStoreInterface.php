<?php

namespace Hubertinio\SyliusKeyValuePlugin\Service;

interface KeyValueStoreInterface
{
    /**
     * Returns the name of this collection.
     */
    public function getCollectionName(): string;

    /**
     * Returns whether a given key exists in the store.
     */
    public function has(string $key): bool;

    /**
     * Returns the stored value for a given key.
     */
    public function get(string $key, mixed $default = NULL): mixed;

    /**
     * Returns the stored key/value pairs for a given set of keys.
     */
    public function getMultiple(array $keys): array;

    /**
     * Returns all stored key/value pairs in the collection.
     */
    public function getAll(): array;

    /**
     * Saves a value for a given key.
     */
    public function set(string $key, mixed $value);

    /**
     * Saves a value for a given key if it does not exist yet.
     */
    public function setIfNotExists(string $key, mixed $value): bool;

    /**
     * Saves key/value pairs.
     */
    public function setMultiple(array $data): void;

    /**
     * Renames a key.
     */
    public function rename(string $key, string $new_key);

    /**
     * Deletes an item from the key/value store.
     */
    public function delete(string $key): void;

    /**
     * Deletes multiple items from the key/value store.
     */
    public function deleteMultiple(array $keys): void;

    /**
     * Deletes all items from the key/value store.
     */
    public function deleteAll(): void;

}