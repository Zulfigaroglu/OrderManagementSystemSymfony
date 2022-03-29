<?php

namespace App\Entity\Infrastructure;

interface EntityInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @return bool
     */
    public function hasSoftDelete(): bool;
}