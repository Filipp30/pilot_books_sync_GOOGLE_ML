<?php

namespace App\Models\Enums;

use Rexlabs\Enum\Enum;

/**
 * @method static self DATE()
 * @method static self DEPARTURE_PLACE()
 * @method static self DEPARTURE_TIME()
 * @method static self ARRIVAL_PLACE()
 * @method static self ARRIVAL_TIME()
 * @method static self AIRCRAFT_MODEL()
 * @method static self AIRCRAFT_REGISTRATION()
 */

class PilotBookFields extends Enum
{
    const DATE = 'date';
    const DEPARTURE_PLACE = 'departure_place';
    const DEPARTURE_TIME = 'departure_time';
    const ARRIVAL_PLACE = 'arrival_place';
    const ARRIVAL_TIME = 'arrival_time';
    const AIRCRAFT_MODEL = 'aircraft_model';
    const AIRCRAFT_REGISTRATION = 'aircraft_registration';
}
