<?php

namespace App\Services\Contracts;

use App\Repository\Dto\PilotBookRowDto;
use Illuminate\Http\File;
use Illuminate\Support\Collection;

interface DocumentHandlerContract
{
    /**
     * @param File $pdf
     * @return  array<PilotBookRowDto>
     */
    public function handlePilotBookDocument(File $pdf): array;
}
