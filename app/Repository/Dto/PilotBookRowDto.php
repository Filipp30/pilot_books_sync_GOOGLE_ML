<?php

namespace App\Repository\Dto;

use App\Models\Enums\Aerodromes;
use App\Models\Enums\PilotBookFields;
use Carbon\Carbon;
use DateTime;

class PilotBookRowDto
{
    public ?string $text;
    public ?string $date;
    public ?string $departurePlace;
    public ?string $departureTime;
    public ?string $arrivalPlace;
    public ?string $arrivalTime;
    public ?string $aircraftModel;
    public ?string $aircraftRegistration;
    public ?array $errors;

    public function __construct(
        ?string     $text,
        ?DateTime  $date,
        ?Aerodromes $departurePlace,
        ?Carbon     $departureTime,
        ?Aerodromes $arrivalPlace,
        ?Carbon     $arrivalTime,
        ?string     $aircraftModel,
        ?string     $aircraftRegistration,
        array       $errors = []
    ) {
        $this->text = $text;
        $this->date = $date?->format('d-m-Y');
        $this->departurePlace = $departurePlace?->value();
        $this->departureTime = $departureTime?->format('H:i');
        $this->arrivalPlace = $arrivalPlace?->value();
        $this->arrivalTime = $arrivalTime?->format('H:i');
        $this->aircraftModel = $aircraftModel;
        $this->aircraftRegistration = $aircraftRegistration;
        $this->errors = $errors;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['text'],
            $data[PilotBookFields::DATE],
            $data[PilotBookFields::DEPARTURE_PLACE],
            $data[PilotBookFields::DEPARTURE_TIME],
            $data[PilotBookFields::ARRIVAL_PLACE],
            $data[PilotBookFields::ARRIVAL_TIME],
            $data[PilotBookFields::AIRCRAFT_MODEL],
            $data[PilotBookFields::DEPARTURE_PLACE],
            $data['errors']
        );
    }
}
