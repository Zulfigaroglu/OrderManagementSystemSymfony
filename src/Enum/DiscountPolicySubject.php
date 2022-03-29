<?php

namespace App\Enum;

use App\Enum\Infrastructure\ArrayableInterface;

class DiscountPolicySubject implements ArrayableInterface
{
    const ORDER = 'order';
    const ANY_ITEM = 'any_item';
    const CHEAPEST_ITEM = 'cheapest_item';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return [
            self::ORDER,
            self::ANY_ITEM,
            self::CHEAPEST_ITEM,
        ];
    }
}
