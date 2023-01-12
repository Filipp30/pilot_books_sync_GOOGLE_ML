<?php

namespace App\Services\GoogleCloud;

use App\Exceptions\ProcessingPilotbookException;
use App\Models\UlmBook;
use App\Repository\Dto\PilotBookInvalidRowsDto;
use App\Repository\Dto\PilotBookRowDto;
use App\Repository\Dto\PilotBookRowDtoFactory;
use App\Repository\Services\UlmBookRepository;
use App\Services\Contracts\DocumentHandlerContract;
use App\Services\Helpers\PilotbookSortHelper;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\DocumentAI\V1\Document;
use Google\Cloud\DocumentAI\V1\Document\Entity;
use Google\Cloud\DocumentAI\V1\DocumentProcessorServiceClient;
use Google\Protobuf\Internal\RepeatedField;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Throwable;

class DocumentAiService implements DocumentHandlerContract
{
    private PilotbookSortHelper $sortHelper;
    private PilotBookRowDtoFactory $pilotBookRowDtoFactory;

    public function __construct(PilotbookSortHelper $sortHelper, PilotBookRowDtoFactory $pilotBookRowDtoFactory)
    {
        $this->sortHelper = $sortHelper;
        $this->pilotBookRowDtoFactory = $pilotBookRowDtoFactory;
    }

    /**
     * @param UploadedFile $file
     * @return Collection<PilotBookRowDto>
     * @throws ProcessingPilotbookException
     */
    public function handle(UploadedFile $file): Collection
    {
        // 1. Get data from GoogleCloud Document AI Api
        try {
            $responseData = $this->getData($file);
        } catch (Throwable $e) {
            throw new ProcessingPilotbookException('External service error', $e);
        }

        // 2. Convert RepeatedField data to array
        $data = $this->handleResponse($responseData);

        // Empty array given when document is not valid or empty
        ### handle error ###
        if (empty($data)) {
            throw new ProcessingPilotbookException('Your document is invalid or not readable');
        }

        // FIXTURE DATA
//        $data = json_decode('[{"record":"14\/08\/22 EBFN 12,30 EBAM 13:10 C-42 00-108 O 40 POLLET","properties":{"date_departure_arrival":"14\/08\/22 EBFN 12,30 EBAM 13:10","aircraft":"C-42 00-108","total_time_of_flight":"O 40","name_pic":"POLLET"}},{"record":"14\/08\/22 EBAM 14:00 EBFN 14:40 C-42 00-108 0 40 POLLET","properties":{"date_departure_arrival":"14\/08\/22 EBAM 14:00 EBFN 14:40","aircraft":"C-42 00-108","total_time_of_flight":"0 40","name_pic":"POLLET"}},{"record":"21\/08\/22 EBFN 12:00 EBFN 13:15 C-42\n00-108 1 15 POLLET","properties":{"date_departure_arrival":"21\/08\/22 EBFN 12:00 EBFN 13:15","aircraft":"C-42\n00-108","total_time_of_flight":"1 15","name_pic":"POLLET"}},{"record":"13\/11\/22 EBPN 12:30 EBFN 13:38 C-42 00-108 1 08 POLLET","properties":{"date_departure_arrival":"13\/11\/22 EBPN 12:30 EBFN 13:38","aircraft":"C-42 00-108","total_time_of_flight":"1 08","name_pic":"POLLET"}},{"record":"13\/11\/22 EBFN 16:00 EBFN 16:13 C-42 00-108 O 13 POLLET","properties":{"date_departure_arrival":"13\/11\/22 EBFN 16:00 EBFN 16:13","aircraft":"C-42 00-108","total_time_of_flight":"O 13","name_pic":"POLLET"}},{"record":"26\/11\/22 EBFN 13:00 EBEN13:47 C-42 00-108 O 47 POLEET","properties":{"date_departure_arrival":"26\/11\/22 EBFN 13:00 EBEN13:47","aircraft":"C-42 00-108","total_time_of_flight":"O 47","name_pic":"POLEET"}},{"record":"1 04 POLLET","properties":{"total_time_of_flight":"1 04","name_pic":"POLLET"}},{"record":"20\/06\/22 EBFN 14.00 EBFN 15,04 C-42\n00-108 1 21 POLLET","properties":{"date_departure_arrival":"20\/06\/22 EBFN 14.00 EBFN 15,04","aircraft":"C-42\n00-108","total_time_of_flight":"1 21","name_pic":"POLLET"}},{"record":"25\/06\/22 EBFN11.30 EBFN 12,51 C-42 00-108 1 10 POLLET","properties":{"date_departure_arrival":"25\/06\/22 EBFN11.30 EBFN 12,51","aircraft":"C-42 00-108","total_time_of_flight":"1 10","name_pic":"POLLET"}},{"record":"26\/06\/22 EBFM 15:00 EBFN 16:10 C-42 00-108 49 POLLET","properties":{"date_departure_arrival":"26\/06\/22 EBFM 15:00 EBFN 16:10","aircraft":"C-42 00-108","total_time_of_flight":"49","name_pic":"POLLET"}},{"record":"10\/07\/22 EBFM 16:00 EBFN 16,49 C-42\n00-108 16\/04\/22 EBFM 11:00 E BFM 12,24 C-42\n00-108 24\/07\/22 EBEN 09:30 EBFN 10;21 C-42 00-108 07\/08\/22 EBEN 10uco EBFN 11:24 C-42 00-108 1 24 POLLET O 51 POLLET 1 27 POLLET","properties":{"date_departure_arrival":"07\/08\/22 EBEN 10uco EBFN 11:24","aircraft":"C-42 00-108","total_time_of_flight":"1 27","name_pic":"POLLET"}},{"record":"28\/08\/22 EBEN 11:00 EBTN 12:04 C-42\n00-108 1 04 POLLET","properties":{"date_departure_arrival":"28\/08\/22 EBEN 11:00 EBTN 12:04","aircraft":"C-42\n00-108","total_time_of_flight":"1 04","name_pic":"POLLET"}},{"record":"08\/10\/22 EBFN 13:30 EBFN 14:35 C-42\n00-108 1 05 POLLET","properties":{"date_departure_arrival":"08\/10\/22 EBFN 13:30 EBFN 14:35","aircraft":"C-42\n00-108","total_time_of_flight":"1 05","name_pic":"POLLET"}},{"record":"22\/10\/22 EBFN 13:30 EBEN 14:45 C-42 00-108 1 15 POLLET","properties":{"date_departure_arrival":"22\/10\/22 EBFN 13:30 EBEN 14:45","aircraft":"C-42 00-108","total_time_of_flight":"1 15","name_pic":"POLLET"}},{"record":"29\/10\/22 EBFN 13:30 EBFN 14:44 C-42 00-108 1 11 POLLET","properties":{"date_departure_arrival":"29\/10\/22 EBFN 13:30 EBFN 14:44","aircraft":"C-42 00-108","total_time_of_flight":"1 11","name_pic":"POLLET"}},{"record":"05\/11\/22 EBFM 13:30 EBFM 14:04 C-42 00-108 O 34 POLLET -820","properties":{"date_departure_arrival":"05\/11\/22 EBFM 13:30 EBFM 14:04","aircraft":"-820","total_time_of_flight":"O 34","name_pic":"POLLET"}}]', true);

        // 3. Convert data to DTO
        $convertedData = array_map(fn(array $data): PilotBookRowDto => $this->pilotBookRowDtoFactory->fromArray($data), $data);

        // 4. sort by date time
        $sorted = $this->sortHelper->sortDescByDateTime($convertedData);

        // 5. filter invalid rows
        $validSeparated = $this->sortHelper->separateValid($sorted);

        // 6. get last date time
        $lastBookRecordByDateTime = UlmBookRepository::getLastRecord();

        ### handle error ###
        if (UlmBook::all()->isNotEmpty() && $lastBookRecordByDateTime === null) {
            throw new ProcessingPilotbookException('Failed to get last book record by date and time');
        }

        // 7. get only after last date time
        $afterDateTimeFiltered = new Collection();
        if ($validSeparated['valid']->isNotEmpty()) {
            $lastBookRecordByDateTime !== null
                ? $afterDateTimeFiltered = $this->sortHelper->filterByAfterDateTime($validSeparated['valid'], $lastBookRecordByDateTime)
                : $afterDateTimeFiltered = $validSeparated['valid'];
        }

        // 8. handle records from invalid
        if ($lastBookRecordByDateTime !== null && !empty($validSeparated['invalid'])) {
            $this->handleInvalidRows($validSeparated['invalid'], $lastBookRecordByDateTime);
        }

        // 9. return valid and not yet existing data-rows
        return $afterDateTimeFiltered;
    }

    /**
     * Get data from GoogleCloud Document AI Api
     *
     * @param UploadedFile $file
     * @return  RepeatedField
     * @throws ApiException|ValidationException
     */
    private function getData(UploadedFile $file): RepeatedField
    {
        $inlineDocument = new Document([
            'mime_type' => 'image/jpeg',
            'content' => file_get_contents($file),
        ]);

        $postBody = [
            'inlineDocument' => $inlineDocument,
            'skipHumanReview' => true,
        ];

        $documentProcessorServiceClient = new DocumentProcessorServiceClient([
            'apiEndpoint' => 'eu-documentai.googleapis.com'
        ]);

        $formattedName = $documentProcessorServiceClient->processorName(
            config('googleCloud.project'),
            config('googleCloud.location', 'eu'),
            config('googleCloud.processor')
        );
        $operationResponse = $documentProcessorServiceClient->processDocument($formattedName, $postBody);

        return $operationResponse->getDocument()->getEntities();
    }

    /**
     * Convert RepeatedField data to array
     *
     * @param RepeatedField $entities
     * @return array<string, string>
     */
    private function handleResponse(RepeatedField $entities): array
    {
        $result = array();
        foreach ($entities as $entity) {
            $result[] = [
                $entity->getType() => $entity->getMentionText(),
                'properties' => array_merge(...array_map(static fn(Entity $property): array =>
                    [
                        $property->getType() => $property->getMentionText(),
                    ], iterator_to_array($entity->getProperties()))
                ),
            ];
        }

        return $result;
    }

    /**
     * Process invalid rows (rows without date or/and arrival_time)
     *
     * @param Collection $invalid
     * @param PilotBookRowDto $lastBookRecordByDateTime
     * @return void
     */
    private function handleInvalidRows(Collection $invalid, PilotBookRowDto $lastBookRecordByDateTime): void
    {
        // TODO: handle this invalid by user and filter when date and time is higher then presented.
        if ($invalid->isNotEmpty()) {
            $invalidDto = PilotBookInvalidRowsDto::fromData($invalid, $lastBookRecordByDateTime);
        }
    }
}
