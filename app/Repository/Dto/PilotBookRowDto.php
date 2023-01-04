<?php

namespace App\Repository\Dto;

use App\Models\Enums\Aerodromes;
use App\Models\Enums\PilotBookFields;
use App\Models\UlmBook;
use Carbon\Carbon;
use DateTime;

class PilotBookRowDto
{
    public ?string $id;
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
        ?string $id,
        ?string $text,
        ?DateTime $date,
        ?Aerodromes $departurePlace,
        ?Carbon $departureTime,
        ?Aerodromes $arrivalPlace,
        ?Carbon $arrivalTime,
        ?string $aircraftModel,
        ?string $aircraftRegistration,
        array $errors = []
    ) {
        $this->id = $id;
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
            null,
            $data['text'] ?? null,
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

    public static function fromDomain(UlmBook $ulmBook): self
    {
        return new self(
            $ulmBook['id'],
            null,
            DateTime::createFromFormat('d-m-Y', $ulmBook[PilotBookFields::DATE]),
            $ulmBook[PilotBookFields::DEPARTURE_PLACE] === null ? null : Aerodromes::tryFrom($ulmBook[PilotBookFields::DEPARTURE_PLACE]),
            Carbon::createFromTimeString($ulmBook[PilotBookFields::DEPARTURE_TIME]),
            $ulmBook[PilotBookFields::ARRIVAL_PLACE] === null ? null : Aerodromes::tryFrom($ulmBook[PilotBookFields::ARRIVAL_PLACE]),
            Carbon::createFromTimeString($ulmBook[PilotBookFields::ARRIVAL_TIME]),
            $ulmBook[PilotBookFields::AIRCRAFT_MODEL],
            $ulmBook[PilotBookFields::AIRCRAFT_REGISTRATION],
            $ulmBook['errors'] === null ?  [] : json_decode($ulmBook['errors'])
        );
    }

    public function getDate(): ?DateTime
    {
       return $this->date === null ? null : DateTime::createFromFormat('d-m-Y', $this->date);
    }

    public function getDeparturePlace(): ?Aerodromes
    {
        return Aerodromes::tryFrom($this->departurePlace)->value();
    }

    public function getDepartureTime(): ?string
    {
        return $this->departureTime === null ? null : Carbon::createFromTimeString($this->departureTime);
    }

    public function getArrivalPlace(): ?Aerodromes
    {
        return Aerodromes::tryFrom($this->arrivalPlace)->value();
    }

    public function getArrivalTime(): ?Carbon
    {
        return $this->arrivalTime === null ? null : Carbon::createFromTimeString($this->arrivalTime);
    }

    public function getAircraftModel(): ?string
    {
        return $this->aircraftModel;
    }

    public function getAircraftRegistration(): ?string
    {
        return $this->aircraftRegistration;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }


}
