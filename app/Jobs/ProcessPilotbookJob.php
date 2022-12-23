<?php

namespace App\Jobs;

use App\Exceptions\ProcessingPilotbookJobException;
use App\Models\Enums\BookTypes;
use App\Models\UlmBook;
use App\Repository\Dto\PilotBookInvalidRowsDto;
use App\Repository\Dto\PilotBookRowDto;
use App\Repository\Services\UlmBookRepository;
use App\Services\Contracts\DocumentHandlerContract;
use App\Services\Helpers\PilotbookSortHelper;
use App\Traits\CollectionHasInstanceOf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Throwable;

class ProcessPilotbookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CollectionHasInstanceOf;

    private BookTypes $type;
    private ?File $pdf;

    public function __construct(BookTypes $type, ?File $pdf = null)
    {
        $this->type = $type;
        $this->pdf = $pdf;
    }

    /**
     * @throws ProcessingPilotbookJobException
     */
    public function handle(
        DocumentHandlerContract $documentAiService,
        UlmBookRepository $repository,
        PilotbookSortHelper $sortHelper
    ): void {
        // Get data from GoogleCloud Document AI Api
        $data = $documentAiService->handlePilotBookDocument($this->pdf);
        ### handle error ###
        if (empty($data)) {
            throw new ProcessingPilotbookJobException(
                '$data = $documentAiService->handlePilotBookDocument($this->pdf);',
                'data is empty'
            );
        }

        // 1. sort by date time
        $sorted = $sortHelper->sortDescByDateTime($data);
        ### handle error ###
        if (!self::hasInstance($sorted, PilotBookRowDto::class)) {
            throw new ProcessingPilotbookJobException(
                'self::hasInstance($sorted, PilotBookRowDto::class)',
                'Sorted by date time data in not instance of PilotBookRowDto'
            );
        }

        // 2. filter invalid rows
        $validSeparated = $sortHelper->separateValid($sorted);

        // 3. get last date time
        $lastBookRecordByDateTime = UlmBookRepository::getLastRecord();

        // 4. get only after last date time
        $afterDateTimeFiltered = new Collection();

        ### handle error ###
        if (UlmBook::all()->isNotEmpty() && $lastBookRecordByDateTime === null) {
            throw new ProcessingPilotbookJobException(
                'UlmBook::all()->isNotEmpty() && $lastBookRecordByDateTime === null',
                'Ulm book is not empty but last record is null'
            );
        }
        if ($validSeparated['valid']?->isNotEmpty()) {
            $lastBookRecordByDateTime !== null
                ? $afterDateTimeFiltered = $sortHelper->filterByAfterDateTime($validSeparated['valid'], $lastBookRecordByDateTime)
                : $afterDateTimeFiltered = $validSeparated['valid'];
        }

        // 5. save
        if ($afterDateTimeFiltered->isNotEmpty()) {
            ### handle error ###
            if (!self::hasInstance($afterDateTimeFiltered, PilotBookRowDto::class)) {
                throw new ProcessingPilotbookJobException(
                    'self::hasInstance($afterDateTimeFiltered, PilotBookRowDto::class)',
                    'afterDateTimeFiltered collection has no type of PilotBookRowDto'
                );
            }

            $repository::saveFromCollection($afterDateTimeFiltered);
        }

        // 6. handle records from invalid
        if ($validSeparated['invalid']?->isNotEmpty()) {
            // TODO: handle this invalid by user and filter when date and time is higher then presented.
            $invalidDto = PilotBookInvalidRowsDto::fromData($validSeparated['invalid'], $lastBookRecordByDateTime);
        }
    }

    /**
     * @throws ProcessingPilotbookJobException
     */
    public function fail(Throwable $exception)
    {
        throw new ProcessingPilotbookJobException(
            'Job Fail',
            $exception->getMessage()
        );
    }
}
