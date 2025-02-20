<?php

namespace Hubertinio\SyliusKeyValuePlugin\Entity;

use DateTime;

interface KeyValueInterface
{
    public function getId(): int;

    public function getKey(): string;

    public function setKey(string $key): KeyValue;

    public function getValue(): mixed;

    public function setValue(mixed $value): KeyValue;

    public function getCollection(): ?string;

    public function setCollection(?string $collection): KeyValue;

    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}