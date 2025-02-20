<?php

namespace Hubertinio\SyliusKeyValuePlugin\Storage;

interface KeyValueStorageInterface
{
    /**
     * Returns whether a given key exists in the store.
     */
    public function has(string $key, ?string $collection = null): bool;

    /**
     * Returns the stored value for a given key.
     */
    public function get(string $key, mixed $default = null, ?string $collection = null): mixed;

    /**
     * Returns the stored key/value pairs for a given set of keys.
     */
    public function getMultiple(array $keys, ?string $collection = null): array;

    /**
     * Returns all stored key/value pairs in the collection.
     */
    public function getAll(?string $collection = null): array;

    /**
     * Saves a value for a given key.
     */
    public function set(string $key, mixed $value, ?string $collection = null): void;

    /**
     * Saves a value for a given key if it does not exist yet.
     */
    public function setIfNotExists(string $key, mixed $value, ?string $collection = null): bool;

    /**
     * Saves key/value pairs.
     */
    public function setMultiple(array $data, ?string $collection = null): void;

    /**
     * Renames a key.
     */
    public function rename(string $key, string $newKey, ?string $collection = null): bool;

    /**
     * Deletes an item from the key/value store.
     */
    public function delete(string $key, ?string $collection = null): void;

    /**
     * Deletes multiple items from the key/value store.
     */
    public function deleteMultiple(array $keys, ?string $collection = null): void;

    /**
     * Deletes all items from the key/value store.
     */
    public function deleteAll(?string $collection = null): void;
}