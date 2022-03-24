<?php

namespace App\Entity\Infrastructure;

interface IEntity
{
    public function getId(): ?int;

    public function getCreatedAt(): ?string;

    public function getUpdatedAt(): ?string;

    public function hasSoftDelete(): bool;
}