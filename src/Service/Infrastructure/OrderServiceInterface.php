<?php

namespace App\Service\Infrastructure;

use App\Entity\Order;


interface OrderServiceInterface
{
    /**
     * @return Order[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return Order
     */
    public function getById(int $id): Order;

    /**
     * @param array $orderData
     * @return Order
     */
    public function create(array $orderData): Order;

    /**
     * @param Order $order
     * @param array $orderData
     * @return Order
     */
    public function update(Order $order, array $orderData): Order;

    /**
     * @param Order $order
     * @return mixed
     */
    public function save(Order $order);

    /**
     * @param Order $order
     * @return mixed
     */
    public function delete(Order $order);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

    /**
     * @param Order $order
     * @param array $orderData
     * @return mixed
     */
    public function updateProperties(Order $order, array $orderData);

    /**
     * @param Order $order
     * @param int $customerId
     * @return mixed
     */
    public function attachCustomerById(Order $order, int $customerId);

    /**
     * @param Order $order
     * @param int $productId
     * @param int $quantity
     * @return mixed
     */
    public function attachProductById(Order $order, int $productId, int $quantity);

    /**
     * @param Order $order
     * @return float
     */
    public function calculateTotal(Order $order): float;
}