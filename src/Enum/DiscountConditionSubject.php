<?php

namespace App\Enum;

use App\Enum\Infrastructure\ArrayableInterface;

class DiscountConditionSubject implements ArrayableInterface
{
    const TOTAL_PRICE = 'total_price';
    const PRODUCT_QUANTITY = 'product_quantity';
    const ITEM_COUNT = 'item_count';

    public static function toArray(): array
    {
        return [
            self::TOTAL_PRICE,
            self::PRODUCT_QUANTITY,
            self::ITEM_COUNT,
        ];
    }
}
