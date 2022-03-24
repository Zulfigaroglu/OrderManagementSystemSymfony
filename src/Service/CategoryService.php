<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\Infrastructure\ICategoryService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService implements ICategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Category[]
     */
    public function getAll(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function getById(int $id): Category
    {
        return $this->categoryRepository->find($id);
    }

    public function create(array $categoryData): Category
    {
        try {
            $category = new Category();
            $this->updateProperties($category, $categoryData);
            return $category;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function update(Category $category, array $categoryData): Category
    {
        try {
            $this->updateProperties($category, $categoryData);
            return $category;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function save(Category $category)
    {
        try {
            $this->categoryRepository->save($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function delete(Category $category)
    {
        try {
            $this->categoryRepository->remove($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function deleteById(int $id)
    {
        try {
            $category = $this->getById($id);
            $this->categoryRepository->remove($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function updateProperties(Category $category, array $categoryData)
    {
        if (array_key_exists('name', $categoryData)) {
            $category->setName($categoryData['name']);
        }
    }
}