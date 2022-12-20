<?php

namespace App\Repository\Dto;

use App\Models\Enums\Aerodromes;
use App\Models\Enums\GoogleProcessorLabels;
use App\Models\Enums\PilotBookFields;
use App\Traits\DateTimeFormatter;
use App\Traits\PilotBookFieldErrorHandler;

class PilotBookRowDtoFactory
{
    use DateTimeFormatter;
    use PilotBookFieldErrorHandler;

    /**
     * Convert all data from google response to DTO
     *
     * @param array<string, mixed> $data
     * @return PilotBookRowDto
     */
    public function fromArray(array $data): PilotBookRowDto {
        $properties = $data['properties'];

        $collection = array_merge(
            ['text' => str_replace("\n", ' ', $data['record'])],
            $this->parseDateDepartureArrival($properties),
            $this->parseAircraft($properties),
        );

        return PilotBookRowDto::fromArray($collection);
    }

    ### Each method must return array of PilotBookFields ###

    /**
     * Get - format - validate: date, departure_place, departure_time, arrival_place, arrival_time
     *
     * @param array<GoogleProcessorLabels, string> $properties
     * @return array<PilotBookFields, string>
     */
    private function parseDateDepartureArrival(array $properties): array
    {
        $errors = [];
        $data = explode(' ', $properties[GoogleProcessorLabels::DATE_DEPARTURE_ARRIVAL], 5);

        $date = self::getDateFromUnknownFormat($data[0]);
        $departurePlace = Aerodromes::tryFrom($data[1]);
        $departureTime = self::getTimeFromUnknownFormat($data[2]);
        $arrivalPlace = Aerodromes::tryFrom($data[3]);
        $arrivalTime = self::getTimeFromUnknownFormat($data[4]);

        // Validate and get field errors
        if ($date === null) {$errors[] = self::getError(PilotBookFields::DATE(), $data[0]); }
        if ($departurePlace === null) {$errors[] = self::getError(PilotBookFields::DEPARTURE_PLACE(), $data[1]); }
        if ($departureTime === null) {$errors[] = self::getError(PilotBookFields::DEPARTURE_TIME(), $data[2]); }
        if ($arrivalPlace === null) {$errors[] = self::getError(PilotBookFields::ARRIVAL_PLACE(), $data[3]); }
        if ($arrivalTime === null) {$errors[] = self::getError(PilotBookFields::ARRIVAL_TIME(), $data[4]); }

        return [
            PilotBookFields::DATE => $date,
            PilotBookFields::DEPARTURE_PLACE => $departurePlace,
            PilotBookFields::DEPARTURE_TIME => $departureTime,
            PilotBookFields::ARRIVAL_PLACE => $arrivalPlace,
            PilotBookFields::ARRIVAL_TIME => $arrivalTime,
            'errors' => $errors,
        ];
    }

    /**
     * Get and validate: aircraft_model, aircraft_registration
     *
     * @param array<GoogleProcessorLabels, string> $properties
     * @return array<PilotBookFields, string>
     */
    private function parseAircraft(array $properties): array
    {
        $propertiesString = $properties[GoogleProcessorLabels::AIRCRAFT];
        $propertiesString = str_replace("\n", ' ', $propertiesString);
        $aircraft = explode(' ', $propertiesString, 2);

        return [
            PilotBookFields::AIRCRAFT_MODEL => $aircraft[0],
            PilotBookFields::AIRCRAFT_REGISTRATION => $aircraft[1],
        ];
    }
}
