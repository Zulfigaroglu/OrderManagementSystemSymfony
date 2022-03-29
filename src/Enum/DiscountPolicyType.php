<?php

namespace App\Enum;

use App\Enum\Infrastructure\ArrayableInterface;

class DiscountPolicyType implements ArrayableInterface
{
    const DISCOUNT_BY_PERCENTAGE = 'discount_by_percantage';
    const DISCOUNT_BY_TOTAL = 'discount_by_total';
    const GIVE_FREE = 'give_free';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return [
            self::DISCOUNT_BY_PERCENTAGE,
            self::DISCOUNT_BY_TOTAL,
            self::GIVE_FREE,
        ];
    }
}
