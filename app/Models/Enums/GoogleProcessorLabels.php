<?php

namespace App\Models\Enums;

use Rexlabs\Enum\Enum;

/**
 * @method static self DATE_DEPARTURE_ARRIVAL()
 * @method static self AIRCRAFT()
 */

class GoogleProcessorLabels extends Enum
{
   const DATE_DEPARTURE_ARRIVAL = 'date_departure_arrival';
   const AIRCRAFT = 'aircraft';
}
