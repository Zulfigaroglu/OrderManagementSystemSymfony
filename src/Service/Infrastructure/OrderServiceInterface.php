<?php

namespace App\Service\Infrastructure;

use App\Entity\Order;

interface OrderServiceInterface
{
    /**
     * @return Order[]
     */
    public function getAll(): array;

    public function getById(int $id): Order;

    public function create(array $orderData): Order;

    public function update(Order $order, array $orderData): Order;

    public function save(Order $order);

    public function delete(Order $order);

    public function deleteById(int $id);

    public function updateProperties(Order $order, array $orderData);

    public function attachCustomerById(Order $order, int $customerId);

    public function attachProductById(Order $order, int $productId, int $quantity);

    public function calculateTotal(Order $order): float;
}