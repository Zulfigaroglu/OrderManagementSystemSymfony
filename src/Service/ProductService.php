<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\NotFoundException;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Infrastructure\ProductServiceInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService implements ProductServiceInterface
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     */
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

    /**
     * @param int $id
     * @return Product
     * @throws NotFoundException
     */
    public function getById(int $id): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new NotFoundException();
        }

        return $product;
    }

    /**
     * @param array $productData
     * @return Product
     */
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

    /**
     * @param Product $product
     * @param array $productData
     * @return Product
     */
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

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product)
    {
        try {
            $this->productRepository->save($product);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Product $product
     * @return void
     */
    public function delete(Product $product)
    {
        try {
            $this->productRepository->remove($product);
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
            $product = $this->getById($id);
            $this->productRepository->remove($product);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Product $product
     * @param array $productData
     * @return void
     */
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

    /**
     * @param Product $product
     * @param int $categoryId
     * @return void
     */
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

    /**
     * @param Product $product
     * @param int $count
     * @return Product
     */
    public function increaseStock(Product $product, int $count): Product
    {
        $increasedStock = $product->getStock() + $count;

        $product->setStock($increasedStock);
        return $product;
    }

    /**
     * @param Product $product
     * @param int $count
     * @return Product
     */
    public function decreaseStock(Product $product, int $count): Product
    {
        $decreasedStock = $product->getStock() - $count;

        $product->setStock($decreasedStock);
        return $product;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return float
     */
    public function calculateTotal(Product $product, int $quantity): float
    {
        $total = $product->getPrice() * $quantity;
        return $total;
    }
}