<?php

namespace App\Model;

use App\Model\Infrastructure\AbstractModel;

class DiscountDetailModel extends AbstractModel
{
    /**
     * @var string
     */
    public string $reason;
    /**
     * @var float
     */
    public float $amount;
    /**
     * @var float
     */
    public float $subtotal;

}