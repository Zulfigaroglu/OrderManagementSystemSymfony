<?php

namespace App\Model;

use App\Model\Infrastructure\Model;

class OrderDiscountsModel extends Model
{
    public int $orderId;

    /**
     * @var DiscountDetailModel[]
     */
    public array $discounts;

    public float $totalDiscount = 0;

    public float $discountedTotal = 0;
}