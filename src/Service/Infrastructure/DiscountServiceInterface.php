<?php

namespace App\Service\Infrastructure;

use App\Entity\Discount;
use App\Entity\Order;
use App\Model\DiscountDetailModel;
use App\Model\OrderDiscountsModel;

interface DiscountServiceInterface
{
    /**
     * @return Discount[]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return Discount
     */
    public function getById(int $id): Discount;

    /**
     * @param array $discountData
     * @return Discount
     */
    public function create(array $discountData): Discount;

    /**
     * @param Discount $discount
     * @param array $discountData
     * @return Discount
     */
    public function update(Discount $discount, array $discountData): Discount;

    /**
     * @param Discount $discount
     * @return mixed
     */
    public function save(Discount $discount);

    /**
     * @param Discount $discount
     * @return mixed
     */
    public function delete(Discount $discount);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);

    /**
     * @param Discount $discount
     * @param array $discountData
     * @return mixed
     */
    public function updateProperties(Discount $discount, array $discountData);

    /**
     * @param Discount $discount
     * @param int $categoryId
     * @return mixed
     */
    public function attachCategoryById(Discount $discount, int $categoryId);

    /**
     * @param int $orderId
     * @return OrderDiscountsModel
     */
    public function calculate(int $orderId): OrderDiscountsModel;

    /**
     * @param int $id
     * @param int $orderId
     * @return Order|null
     */
    public function apply(int $id, int $orderId): ?Order;

    /**
     * @param Discount $discount
     * @param Order $order
     * @return DiscountDetailModel|null
     */
    public function applyDiscountToOrder(Discount $discount, Order $order): ?DiscountDetailModel;
}