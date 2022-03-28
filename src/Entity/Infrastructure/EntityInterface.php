<?php

namespace App\Entity\Infrastructure;

interface EntityInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): ?string;

    public function getUpdatedAt(): ?string;

    public function hasSoftDelete(): bool;
}