<?php

namespace App\Repository\Dto;

use App\Models\Enums\GoogleProcessorLabels;
use App\Models\Enums\PilotBookFields;
use App\Traits\RawCredentialsFormatter;
use App\Traits\PilotBookFieldErrorHandler;

class PilotBookRowDtoFactory
{
    use RawCredentialsFormatter;
    use PilotBookFieldErrorHandler;

    /**
     * Convert all data from google response to DTO
     *
     * @param array<string, mixed> $data
     * @return PilotBookRowDto
     */
    public function fromArray(array $data): PilotBookRowDto {
        $dateDepartureArrival = '';
        $aircraft = '';
        $totalTimeOfFlight = '';
        $namePic = '';

        if (array_key_exists(GoogleProcessorLabels::DATE_DEPARTURE_ARRIVAL, $data['properties'])) {
            $dateDepartureArrival = $data['properties'][GoogleProcessorLabels::DATE_DEPARTURE_ARRIVAL];
        }

        if (array_key_exists(GoogleProcessorLabels::AIRCRAFT, $data['properties'])) {
            $aircraft = $data['properties'][GoogleProcessorLabels::AIRCRAFT];
        }

        if (array_key_exists(GoogleProcessorLabels::TOTAL_TIME_OF_FLIGHT, $data['properties'])) {
            $totalTimeOfFlight = $data['properties'][GoogleProcessorLabels::TOTAL_TIME_OF_FLIGHT];
        }

        if (array_key_exists(GoogleProcessorLabels::NAME_PIC, $data['properties'])) {
            $namePic = $data['properties'][GoogleProcessorLabels::NAME_PIC];
        }

        $collection = array_merge(
            ['text' => str_replace("\n", ' ', $data['record'])],
            $this->parseDateDepartureArrival($dateDepartureArrival),
            $this->parseAircraft($aircraft),
            $this->parseTotalTimeOfFlight($totalTimeOfFlight),
            $this->parseNamePic($namePic)
        );

        return PilotBookRowDto::fromArray($collection);
    }

    ### Each method must return array of PilotBookFields ###

    /**
     * date, departure_place, departure_time, arrival_place, arrival_time
     * Formatting logic is inside RawCredentialsFormatter Trait
     * For extra fields you can add extra formatting logic
     *
     * @param string $dateDepartureArrival
     * @return array<PilotBookFields, string>
     */
    private function parseDateDepartureArrival(string $dateDepartureArrival): array
    {
        $errors = [];
        $data = explode(' ', $dateDepartureArrival, 5);

        $date = array_key_exists(0, $data) ? self::getDateFromUnknownFormat($data[0]) : null;
        $departurePlace = array_key_exists(1, $data) ? self::getAerodromeFromUnknownFormat($data[1]) : null;
        $departureTime = array_key_exists(2, $data) ? self::getTimeFromUnknownFormat($data[2]) : null;
        $arrivalPlace = array_key_exists(3, $data) ? self::getAerodromeFromUnknownFormat($data[3]) : null;
        $arrivalTime = array_key_exists(4, $data) ? self::getTimeFromUnknownFormat($data[4]) : null;

        // Validate and get field errors
        if ($date === null) {$errors[] = self::getError(PilotBookFields::DATE(), array_key_exists(0, $data) ? $data[0] : 'not readable'); }
        if ($departurePlace === null) {$errors[] = self::getError(PilotBookFields::DEPARTURE_PLACE(), array_key_exists(1, $data) ? $data[1] : 'not readable'); }
        if ($departureTime === null) {$errors[] = self::getError(PilotBookFields::DEPARTURE_TIME(), array_key_exists(2, $data) ? $data[2] : 'not readable'); }
        if ($arrivalPlace === null) {$errors[] = self::getError(PilotBookFields::ARRIVAL_PLACE(), array_key_exists(3, $data) ? $data[3] : 'not readable'); }
        if ($arrivalTime === null) {$errors[] = self::getError(PilotBookFields::ARRIVAL_TIME(), array_key_exists(4, $data) ? $data[4] : 'not readable'); }

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
     * aircraft_model, aircraft_registration
     *
     * @param string $aircraft
     * @return array<PilotBookFields, string>
     */
    private function parseAircraft(string $aircraft): array
    {
        $aircraft = explode(' ', str_replace("\n", ' ', $aircraft), 2);

        return [
            PilotBookFields::AIRCRAFT_MODEL => array_key_exists(0, $aircraft) ? $aircraft[0] : 'not readable',
            PilotBookFields::AIRCRAFT_REGISTRATION => array_key_exists(1, $aircraft) ? $aircraft[1] : 'not readable',
        ];
    }

    /**
     * total_time_of_flight
     *
     * @param string $totalTimeOfFlight
     * @return array<PilotBookFields, string>
     */
    private function parseTotalTimeOfFlight(string $totalTimeOfFlight): array
    {
        return [
            PilotBookFields::TOTAL_TIME_OF_FLIGHT => self::getTimeFromUnknownFormat(trim($totalTimeOfFlight))
        ];
    }

    /**
     * name_pic
     *
     * @param string $namePic
     * @return array<PilotBookFields, string>
     */
    private function parseNamePic(string $namePic): array
    {
        return [
            PilotBookFields::NAME_PIC => $namePic
        ];
    }
}
