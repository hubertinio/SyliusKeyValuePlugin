<?php

namespace Hubertinio\SyliusKeyValuePlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface KeyValueInterface extends
    ResourceInterface
{
    public function getId(): int;

    public function getKey(): string;

    public function setKey(string $key): KeyValue;

    public function getValue(): mixed;

    public function setValue(mixed $value): KeyValue;

    public function getCollection(): ?string;

    public function setCollection(?string $collection): KeyValue;
}