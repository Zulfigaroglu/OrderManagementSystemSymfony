<?php

namespace App\Model;

use App\Entity\Discount;
use App\Model\Infrastructure\Model;

class DiscountDetailModel extends Model
{
    public string $reason;
    public float $amount;
    public float $subtotal;

}