<?php

namespace App\Traits;

use App\Models\Enums\PilotBookFields;

trait PilotBookFieldErrorHandler
{
    protected static function getError(PilotBookFields $field, string $currentText): array
    {
        return [
            $field->key() => $currentText,
            'message' => self::getErrorDescription($field),
        ];
    }

    private static function getErrorDescription(PilotBookFields $field): string
    {
        return match ($field->key()) {
            PilotBookFields::DATE => 'Date is not valid.',
            PilotBookFields::DEPARTURE_PLACE => 'Departure place is not valid.',
            PilotBookFields::DEPARTURE_TIME => 'Departure time is not valid.',
            PilotBookFields::ARRIVAL_PLACE => 'Arrival place is not valid.',
            PilotBookFields::ARRIVAL_TIME => 'Arrival time is not valid.',
            PilotBookFields::AIRCRAFT_MODEL => 'Aircraft model is not valid.',
            PilotBookFields::AIRCRAFT_REGISTRATION => 'Aircraft registration is not valid.',
            default => 'Invalid credentials',
        };
    }
}
