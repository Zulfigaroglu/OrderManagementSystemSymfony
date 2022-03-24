<?php

namespace App\Enum;

use App\Enum\Infrastructure\Arrayable;

class DiscountConditionType implements Arrayable
{
    const HIGHIER_THAN_VALUE = 'higher_than_value';
    const EACH_TIMES_OF_VALUE = 'each_times_of_value';

    public static function toArray(): array
    {
        return  [
            self::HIGHIER_THAN_VALUE,
            self::EACH_TIMES_OF_VALUE,
        ];
    }
}
