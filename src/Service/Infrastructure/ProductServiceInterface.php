<?php

namespace App\Service\Infrastructure;

use App\Entity\Product;

interface ProductServiceInterface
{
    /**
     * @return Product[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return Product
     */
    public function getById(int $id): Product;

    /**
     * @param array $productData
     * @return Product
     */
    public function create(array $productData): Product;

    /**
     * @param Product $product
     * @param array $productData
     * @return Product
     */
    public function update(Product $product, array $productData): Product;

    /**
     * @param Product $product
     * @return mixed
     */
    public function save(Product $product);

    /**
     * @param Product $product
     * @return mixed
     */
    public function delete(Product $product);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

    /**
     * @param Product $product
     * @param array $productData
     * @return mixed
     */
    public function updateProperties(Product $product, array $productData);

    /**
     * @param Product $product
     * @param int $categoryId
     * @return mixed
     */
    public function attachCategoryById(Product $product, int $categoryId);

    /**
     * @param Product $product
     * @param int $count
     * @return Product
     */
    public function increaseStock(Product $product, int $count): Product;

    /**
     * @param Product $product
     * @param int $count
     * @return Product
     */
    public function decreaseStock(Product $product, int $count): Product;

    /**
     * @param Product $product
     * @param int $quantity
     * @return float
     */
    public function calculateTotal(Product $product, int $quantity): float;
}