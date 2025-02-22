<?php

namespace Hubertinio\SyliusKeyValuePlugin\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Hubertinio\SyliusKeyValuePlugin\Repository\KeyValueRepository;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KeyValueRepository::class)]
#[ORM\Table(name: 'hubertinio_key_value')]
#[ORM\UniqueConstraint(name: 'key_collection', columns: ['`key`', 'collection'])]
class KeyValue implements KeyValueInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: '`key`', type: 'string', length: 255, unique: false)]
    #[Groups(['admin:key_value:read', 'admin:key_value:write'])]
    private string $key;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['admin:key_value:read', 'admin:key_value:write'])]
    private mixed $value = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['admin:key_value:read', 'admin:key_value:write'])]
    private ?string $collection = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;

    public function __construct(
        string $key,
        mixed $value = null,
        ?string $collection = null
    ) {
        $this->key = $key;
        $this->value = $value;
        $this->collection = $collection;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): self
    {
        $this->collection = $collection;
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
