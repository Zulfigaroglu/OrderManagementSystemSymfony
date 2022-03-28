<?php

namespace App\Model;

use App\Model\Infrastructure\AbstractModel;

class DiscountDetailModel extends AbstractModel
{
    public string $reason;
    public float $amount;
    public float $subtotal;

}