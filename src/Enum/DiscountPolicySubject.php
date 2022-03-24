<?php

namespace App\Enum;

use App\Enum\Infrastructure\Arrayable;

class DiscountPolicySubject implements Arrayable
{
    const ORDER = 'order';
    const ANY_ITEM = 'any_item';
    const CHEAPEST_ITEM = 'cheapest_item';

    public static function toArray(): array
    {
        return [
            self::ORDER,
            self::ANY_ITEM,
            self::CHEAPEST_ITEM,
        ];
    }
}
