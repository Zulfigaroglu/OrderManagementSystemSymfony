<?php

namespace App\Service\Infrastructure;

use App\Entity\Discount;
use App\Entity\Order;
use App\Model\DiscountDetailModel;
use App\Model\OrderDiscountsModel;
use Doctrine\Common\Collections\Collection;

interface IDiscountService
{
    /**
     * @return Discount[]
     */
    public function getAll(): array;

    public function getById(int $id): Discount;

    public function create(array $discountData): Discount;

    public function update(Discount $discount, array $discountData): Discount;

    public function save(Discount $discount);

    public function delete(Discount $discount);

    public function deleteById(int $id);

    public function updateProperties(Discount $discount, array $discountData);

    public function attachCategoryById(Discount $discount, int $categoryId);

    public function calculate(int $orderId): OrderDiscountsModel;

    public function apply(int $id,int $orderId): ?Order;

    public function applyDiscountToOrder(Discount $discount, Order $order): ?DiscountDetailModel;
}