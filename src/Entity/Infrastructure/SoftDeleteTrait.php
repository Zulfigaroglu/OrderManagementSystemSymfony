<?php

namespace App\Entity\Infrastructure;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeleteTrait
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?\DateTimeImmutable $deletedAt = null;

    /**
     * @return string|null
     */
    public function getDeletedAt(): ?string
    {
        return $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null;
    }

    /**
     * @return bool
     */
    public function hasSoftDelete(): bool
    {
        return true;
    }
}