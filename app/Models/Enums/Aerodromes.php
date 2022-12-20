<?php

namespace App\Models\Enums;

use Rexlabs\Enum\Enum;

/**
 * @method static self EBFN()
 */
class Aerodromes extends Enum
{
    const EBFN = 'EBFN';

    public static function map() : array
    {
        return [
            static::EBFN => 'EBFN',
        ];
    }

    public static function tryFrom(string $value): ?Enum
    {
        try {
            return static::instanceFromKey(strtoupper($value));
        } catch (\Exception $exception) {
            return null;
        }
    }
}
