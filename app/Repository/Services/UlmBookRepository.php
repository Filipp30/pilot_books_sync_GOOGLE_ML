<?php

namespace App\Repository\Services;

use App\Models\Enums\BookTypes;
use App\Models\Enums\PilotBookFields;
use App\Models\UlmBook;
use App\Repository\Dto\PilotBookRowDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class UlmBookRepository
{
    /**
     * @param Collection<PilotBookRowDto> $collection
     */
    static public function saveFromCollection(Collection $collection): void
    {
        $collection->map(static fn(PilotBookRowDto $dto) => self::save($dto));
    }

    /**
     * @return null|PilotBookRowDto
     */
    static public function getLastRecord(): null|PilotBookRowDto
    {
        if (UlmBook::all()->isEmpty()) {
            return null;
        }

        $ulmBookRecords = UlmBook::all()->groupBy('date')->sortDesc()->collapse()->take(18);

        return $ulmBookRecords->map(fn(UlmBook $record): PilotBookRowDto => PilotBookRowDto::fromDomain($record))
            ->sortBy(fn(PilotBookRowDto $dto) => $dto->getDate())  // By date
            ->groupBy('date')                              // Group by date for sorting by time
            ->map(fn ($group) => $group->sortBy(fn(PilotBookRowDto $dto) => $dto->getArrivalTime()))
            ->collapse()
            ->last();
    }

    /**
     * @return Collection<PilotBookRowDto> $collection
     */
    static public function getAll(BookTypes $type): Collection
    {
        if ($type->is(BookTypes::ULM())) {
            return UlmBook::all()
                ->groupBy('date')
                ->map(fn ($group) => $group->sortBy(fn($group) => $group['arrival_time']))
                ->sortBy('date')
                ->collapse()
                ->map(fn(UlmBook $record): PilotBookRowDto => PilotBookRowDto::fromDomain($record));
        }

        throw new ModelNotFoundException();
    }

    static private function save(PilotBookRowDto $dto): void
    {
        UlmBook::create([
            PilotBookFields::DATE => $dto->date,
            PilotBookFields::DEPARTURE_PLACE => $dto->departurePlace,
            PilotBookFields::DEPARTURE_TIME => $dto->departureTime,
            PilotBookFields::ARRIVAL_PLACE => $dto->arrivalPlace,
            PilotBookFields::ARRIVAL_TIME => $dto->arrivalTime,
            PilotBookFields::AIRCRAFT_MODEL => $dto->aircraftModel,
            PilotBookFields::AIRCRAFT_REGISTRATION => $dto->aircraftRegistration,
            'errors' => json_encode($dto->errors),
        ]);
    }
}
