<?php

namespace App\Service;

use App\Entity\Category;
use App\Exception\NotFoundException;
use App\Repository\CategoryRepository;
use App\Service\Infrastructure\CategoryServiceInterface;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
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

    /**
     * @param int $id
     * @return Category
     * @throws NotFoundException
     */
    public function getById(int $id): Category
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundException();
        }

        return $category;
    }

    /**
     * @param array $categoryData
     * @return Category
     */
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

    /**
     * @param Category $category
     * @param array $categoryData
     * @return Category
     */
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

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category)
    {
        try {
            $this->categoryRepository->save($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Category $category
     * @return void
     */
    public function delete(Category $category)
    {
        try {
            $this->categoryRepository->remove($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteById(int $id)
    {
        try {
            $category = $this->getById($id);
            $this->categoryRepository->remove($category);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Category $category
     * @param array $categoryData
     * @return void
     */
    public function updateProperties(Category $category, array $categoryData)
    {
        if (array_key_exists('name', $categoryData)) {
            $category->setName($categoryData['name']);
        }
    }
}