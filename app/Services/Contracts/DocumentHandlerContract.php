<?php

namespace App\Services\Contracts;

use App\Repository\Dto\PilotBookRowDto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface DocumentHandlerContract
{
    /**
     * @param UploadedFile $file
     * @return Collection<PilotBookRowDto>
     */
    public function handle(UploadedFile $file): Collection;
}
