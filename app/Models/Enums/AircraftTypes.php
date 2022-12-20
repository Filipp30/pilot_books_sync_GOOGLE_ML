<?php

namespace App\Models\Enums;

use Rexlabs\Enum\Enum;

/**
 * @method static self ULM()
 * @method static self PPL()
 */

class AircraftTypes extends Enum
{
    const ULM = 'ULM';
    const PPL = 'PPL';

    public static function map() : array
    {
        return [
            static::ULM => 'ULM',
            static::PPL => 'ULM',
        ];
    }
}
