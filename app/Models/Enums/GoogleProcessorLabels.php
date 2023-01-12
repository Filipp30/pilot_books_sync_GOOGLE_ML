<?php

namespace App\Models\Enums;

use Rexlabs\Enum\Enum;

/**
 * @method static self DATE_DEPARTURE_ARRIVAL()
 * @method static self AIRCRAFT()
 * @method static self TOTAL_TIME_OF_FLIGHT()
 * @method static self NAME_PIC()
 */

class GoogleProcessorLabels extends Enum
{
   const DATE_DEPARTURE_ARRIVAL = 'date_departure_arrival';
   const AIRCRAFT = 'aircraft';
   const TOTAL_TIME_OF_FLIGHT = 'total_time_of_flight';
   const NAME_PIC = 'name_pic';
}
