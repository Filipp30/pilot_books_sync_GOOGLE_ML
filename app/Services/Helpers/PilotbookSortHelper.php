<?php

namespace App\Services\Helpers;

use App\Repository\Dto\PilotBookRowDto;
use Illuminate\Support\Collection;

class PilotbookSortHelper
{
    /**
     * Sort array<PilotBookRowDto>:
     *            first by date
     *            then by arrivalTime or departureTime if arrivalTime is null
     *
     * @param array<PilotBookRowDto> $data
     * @return Collection<PilotBookRowDto>
     */
    public function sortDescByDateTime(array $data): Collection
    {
        return collect($data)
            ->sortBy(fn(PilotBookRowDto $dto) => $dto->getDate())  // By date
            ->groupBy('date')                              // Group by date for sorting by time
            ->map(fn ($group) => $group->sortBy(fn(PilotBookRowDto $dto) => $dto->getArrivalTime() ?? $dto->getDepartureTime()))
            ->collapse();
    }

    /**
     * Separate rows if Date or ArrivalTime is equals null
     *
     * @param Collection<PilotBookRowDto> $collection
     * @return array
     */
    public function separateValid(Collection $collection): array
    {
        return [
            'valid' =>   $collection->filter(fn(PilotBookRowDto $dto) => !($dto->date === null || $dto->arrivalTime === null)),
            'invalid' => $collection->filter(fn(PilotBookRowDto $dto) => ($dto->date === null || $dto->arrivalTime === null)),
        ];
    }

    /**
     *
     * @param Collection<PilotBookRowDto> $collection
     * @param PilotBookRowDto $lastDateTimeRow
     * @return Collection<PilotBookRowDto>
     */
    public function filterByAfterDateTime(Collection $collection, PilotBookRowDto $lastDateTimeRow): Collection
    {
        return $collection->filter(function(PilotBookRowDto $dto) use ($lastDateTimeRow) {
                if ($dto->getDate() > $lastDateTimeRow->getDate()) {
                    return true;
                } else if (($dto->getDate() == $lastDateTimeRow->getDate()) && ($dto->getArrivalTime() > $lastDateTimeRow->getArrivalTime())) {
                    return true;
                }

                return false;
            }
        );
    }
}
