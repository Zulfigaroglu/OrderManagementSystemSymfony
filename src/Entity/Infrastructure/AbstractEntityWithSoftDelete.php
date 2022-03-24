<?php

namespace App\Entity\Infrastructure;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntityWithSoftDelete extends AbstractEntity implements ISoftDeletable
{
    protected $serializeFields = [
        'id',
        'createdAt',
        'createdAt',
        'deletedAt',
    ];

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null;
    }

    public function hasSoftDelete(): bool
    {
        return true;
    }
}