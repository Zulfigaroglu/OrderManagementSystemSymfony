<?php

namespace App\Service;

use App\Entity\Discount;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Infrastructure\IProductService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService implements IProductService
{
    protected ProductRepository $productRepository;
    protected CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Product[]
     */
    public function getAll(): array
    {
        return $this->productRepository->findAll();
    }

    public function getById(int $id): Product
    {
        return $this->productRepository->find($id);
    }

    public function create(array $productData): Product
    {
        try {
            $product = new Product();
            $this->updateProperties($product, $productData);
            return $product;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function update(Product $product, array $productData): Product
    {
        try {
            $this->updateProperties($product, $productData);
            return $product;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function save(Product $product)
    {
        try {
            $this->productRepository->save($product);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function delete(Product $product)
    {
        try {
            $this->productRepository->remove($product);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function deleteById(int $id)
    {
        try {
            $product = $this->getById($id);
            $this->productRepository->remove($product);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function updateProperties(Product $product, array $productData)
    {
        if (array_key_exists('categoryId', $productData)) {
            $this->attachCategoryById($product, $productData['categoryId']);
        }

        if (array_key_exists('name', $productData)) {
            $product->setName($productData['name']);
        }

        if (array_key_exists('price', $productData)) {
            $product->setPrice($productData['price']);
        }

        if (array_key_exists('stock', $productData)) {
            $product->setStock($productData['stock']);
        }
    }

    public function attachCategoryById(Product $product, int $categoryId)
    {
        if ($categoryId) {
            $product->setCategory(null);
            return;
        }

        $category = $this->categoryRepository->find($categoryId);
        if ($category) {
            $product->setCategory($category);
        }
    }

    public function calculateTotal(int $id, int $quantity): float
    {
        $product = $this->getById($id);
        $total = $product->getPrice() * $quantity;
        return $total;
    }
}