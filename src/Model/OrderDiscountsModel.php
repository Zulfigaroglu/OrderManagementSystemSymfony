<?php

namespace App\Model;

use App\Model\Infrastructure\AbstractModel;

class OrderDiscountsModel extends AbstractModel
{
    /**
     * @var int
     */
    public int $orderId;

    /**
     * @var DiscountDetailModel[]
     */
    public array $discounts;

    /**
     * @var float|int
     */
    public float $totalDiscount = 0;

    /**
     * @var float|int
     */
    public float $discountedTotal = 0;
}