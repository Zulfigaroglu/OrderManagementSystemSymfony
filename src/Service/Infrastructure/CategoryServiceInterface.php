<?php

namespace App\Service\Infrastructure;

use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * @return Category[]
     */
    public function getAll(): array;

    public function getById(int $id): Category;

    public function create(array $categoryData): Category;

    public function update(Category $category, array $categoryData): Category;

    public function save(Category $category);

    public function delete(Category $category);

    public function deleteById(int $id);

    public function updateProperties(Category $category, array $categoryData);
}