<?php

namespace App\Service\Infrastructure;

use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * @return Category[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return Category
     */
    public function getById(int $id): Category;

    /**
     * @param array $categoryData
     * @return Category
     */
    public function create(array $categoryData): Category;

    /**
     * @param Category $category
     * @param array $categoryData
     * @return Category
     */
    public function update(Category $category, array $categoryData): Category;

    /**
     * @param Category $category
     * @return mixed
     */
    public function save(Category $category);

    /**
     * @param Category $category
     * @return mixed
     */
    public function delete(Category $category);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

    /**
     * @param Category $category
     * @param array $categoryData
     * @return mixed
     */
    public function updateProperties(Category $category, array $categoryData);
}