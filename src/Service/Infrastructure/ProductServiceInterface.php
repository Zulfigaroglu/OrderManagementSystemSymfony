<?php

namespace App\Service\Infrastructure;

use App\Entity\Product;

interface ProductServiceInterface
{
    /**
     * @return Product[]
     */
    public function getAll(): array;

    public function getById(int $id): Product;

    public function create(array $productData): Product;

    public function update(Product $product, array $productData): Product;

    public function save(Product $product);

    public function delete(Product $product);

    public function deleteById(int $id);

    public function updateProperties(Product $product, array $productData);

    public function attachCategoryById(Product $product, int $categoryId);

    public function increaseStock(Product $product, int $count): Product;

    public function decreaseStock(Product $product, int $count): Product;

    public function calculateTotal(Product $product, int $quantity): float;
}