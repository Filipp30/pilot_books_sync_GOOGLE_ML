<?php

namespace App\Repository\Dto;

use Illuminate\Support\Collection;

class PilotBookInvalidRowsDto
{
    public Collection $invalidRows;
    public ?PilotBookRowDto $lastBookRecordByDateTime;

    /**
     * @param Collection<PilotBookRowDto> $invalidRows
     * @param null|PilotBookRowDto $lastBookRecordByDateTime
     */
    public function __construct(Collection $invalidRows, ?PilotBookRowDto $lastBookRecordByDateTime)
    {
        $this->invalidRows = $invalidRows;
        $this->lastBookRecordByDateTime = $lastBookRecordByDateTime;
    }

    public static function fromData(
        Collection $invalidRows,
        ?PilotBookRowDto $lastBookRecordByDateTime = null
    ): self {
        return new self($invalidRows, $lastBookRecordByDateTime);
    }
}
