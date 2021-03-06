<?php

namespace App\Entity\Infrastructure;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity implements EntityInterface, JsonSerializable
{
    /**
     * @var string[]
     */
    protected $serializeFields = [
        'id',
        'createdAt',
        'updatedAt',
    ];

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    protected \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     */
    protected ?\DateTimeImmutable $updatedAt = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null;
    }

    /**
     * @return bool
     */
    public function hasSoftDelete(): bool
    {
        return false;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateCallback(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->serializeFields as $serializeField) {
            $getterMethodName = "get" . ucfirst($serializeField);
            $value = $this->$getterMethodName();
            $data[$serializeField] = $value;
        }
        return $data;
    }
}