<?php

namespace App\Entity\Infrastructure;

interface ISoftDeletable
{
    public function getDeletedAt(): ?string;
}