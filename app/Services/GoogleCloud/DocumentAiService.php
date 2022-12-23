<?php

namespace App\Services\GoogleCloud;

use App\Repository\Dto\PilotBookRowDto;
use App\Services\Contracts\DocumentHandlerContract;
use Google\Cloud\DocumentAI\V1\Document;
use Google\Cloud\DocumentAI\V1\Document\Entity;
use Google\Cloud\DocumentAI\V1\DocumentProcessorServiceClient;
use Google\Protobuf\Internal\RepeatedField;
use Illuminate\Http\File;

class DocumentAiService implements DocumentHandlerContract
{
    /**
     * @param File $pdf
     * @return  array<PilotBookRowDto>
     */
    public function handlePilotBookDocument(?File $pdf = null): array
    {
//        $inlineDocument = new Document([
//            'mime_type' => 'application/pdf',
//            'content' => file_get_contents('/Users/dev/Desktop/_/pilot_app/pilot_logbook_synchronization_BE/app/Services/GoogleCloud/pilot_book.pdf'),
//        ]);
//
//        $postBody = [
//            'inlineDocument' => $inlineDocument,
//            'skipHumanReview' => true,
//        ];
//
//        $documentProcessorServiceClient = new DocumentProcessorServiceClient([
//            'apiEndpoint' => 'eu-documentai.googleapis.com'
//        ]);
//
//        $formattedName = $documentProcessorServiceClient->processorName('ocr-laravel-49288', 'eu', 'd7d7541e3c06fd7d');
//        $operationResponse = $documentProcessorServiceClient->processDocument($formattedName, $postBody);
//        $entities = $operationResponse->getDocument()->getEntities();
//
//        $result = $this->handleResponse($entities);

        $string = '[{"record":"22\/10\/22 EBFN 13:30 EBEN 14:45 C-42\n00-108","properties":{"date_departure_arrival":"22\/10\/22 EBFN 13:30 EBEN 14:45","aircraft":"C-42\n00-108"}},{"record":"05\/11\/22 EBFM 13:30 EBM 14:04 C-42 100-108","properties":{"date_departure_arrival":"05\/11\/22 EBFM 13:30 EBM 14:04","aircraft":"C-42 100-108"}},{"record":"26\/06\/22 EBFM 15:00 EBFN 16:10 C-42 00-108","properties":{"date_departure_arrival":"26\/06\/22 EBFM 15:00 EBFN 16:10","aircraft":"C-42 00-108"}},{"record":"29\/10\/22 EBFN 13:30 EBFN 14:44 C-42 00-108","properties":{"date_departure_arrival":"29\/10\/22 EBFN 13:30 EBFN 14:44","aircraft":"C-42 00-108"}},{"record":"26\/06\/22 EBFN 11.30 EBFN 12,51 C-42 00-108","properties":{"date_departure_arrival":"26\/06\/22 EBFN 11.30 EBFN 12,51","aircraft":"C-42 00-108"}},{"record":"16\/07\/22 EBFM 11:00 E BFM 12,24 C-42 00-108","properties":{"date_departure_arrival":"16\/07\/22 EBFM 11:00 E BFM 12,24","aircraft":"C-42 00-108"}},{"record":"08\/10\/22 EBFN 13:30 EBFN 14:35 C-42\n00-108","properties":{"date_departure_arrival":"08\/10\/22 EBFN 13:30 EBFN 14:35","aircraft":"C-42\n00-108"}},{"record":"20\/06\/22 EBFN 14.00 EBFN 15.04 C-42 00-108","properties":{"date_departure_arrival":"20\/06\/22 EBFN 14.00 EBFN 15.04","aircraft":"C-42 00-108"}},{"record":"13\/11\/22 EBPN 12:30 E BTN 13:38 C-42 00-108","properties":{"date_departure_arrival":"13\/11\/22 EBPN 12:30 E BTN 13:38","aircraft":"C-42 00-108"}},{"record":"13\/11\/22 EBFN 16:00 EBFN 16:13 C-42 00-108","properties":{"date_departure_arrival":"13\/11\/22 EBFN 16:00 EBFN 16:13","aircraft":"C-42 00-108"}},{"record":"14\/08\/22 EBFN 12,30 EBAM 13:10 C-42 00-108","properties":{"date_departure_arrival":"14\/08\/22 EBFN 12,30 EBAM 13:10","aircraft":"C-42 00-108"}},{"record":"21\/08\/22 EBFN 12:00 EBFN 13:15 C-42 100-108","properties":{"date_departure_arrival":"21\/08\/22 EBFN 12:00 EBFN 13:15","aircraft":"C-42 100-108"}},{"record":"10\/07\/22 EBFN 16:00 EBFN 16.49 C-42 00-108","properties":{"date_departure_arrival":"10\/07\/22 EBFN 16:00 EBFN 16.49","aircraft":"C-42 00-108"}},{"record":"28\/08\/22 EBEN 11:00 EBFN 12:04 C-42\n00-108","properties":{"date_departure_arrival":"28\/08\/22 EBEN 11:00 EBFN 12:04","aircraft":"C-42\n00-108"}},{"record":"24\/07\/22 EBEN 09:30 EBFN 10,21 C-42 00-108","properties":{"date_departure_arrival":"24\/07\/22 EBEN 09:30 EBFN 10,21","aircraft":"C-42 00-108"}},{"record":"14\/08\/22 EBAM 14:00 EBFN 14:40 C-42 100-108","properties":{"date_departure_arrival":"14\/08\/22 EBAM 14:00 EBFN 14:40","aircraft":"C-42 100-108"}},{"record":"26\/eb EBFN 13:00 EBFN 13:47 C-42 00-108","properties":{"date_departure_arrival":"26\/eb EBFN 13:00 EBFN 13:47","aircraft":"C-42 00-108"}},{"record":"07\/08\/22 ERF 10uco EBFN 11: 2-42 00-108","properties":{"date_departure_arrival":"07\/08\/22 ERF 10uco EBFN 11:","aircraft":"2-42 00-108"}}]';
//        $string = '[{"record":"22\/10\/22 EBFN 13:30 EBEN 14:45 C-42\n00-108","properties":{"date_departure_arrival":"22\/10\/22 EBFN 13:30 EBEN 14:45","aircraft":"C-42\n00-108"}},{"record":"05\/11\/22 EBFM 13:30 EBM 14:04 C-42 100-108","properties":{"date_departure_arrival":"05\/11\/22 EBFM 13:30 EBM 14:04","aircraft":"C-42 100-108"}},{"record":"26\/06\/22 EBFM 15:00 EBFN 16:10 C-42 00-108","properties":{"date_departure_arrival":"26\/06\/22 EBFM 15:00 EBFN 16:10","aircraft":"C-42 00-108"}},{"record":"29\/10\/22 EBFN 13:30 EBFN 14:44 C-42 00-108","properties":{"date_departure_arrival":"29\/10\/22 EBFN 13:30 EBFN 14:44","aircraft":"C-42 00-108"}},{"record":"26\/06\/22 EBFN 11.30 EBFN 12,51 C-42 00-108","properties":{"date_departure_arrival":"26\/06\/22 EBFN 11.30 EBFN 12,51","aircraft":"C-42 00-108"}},{"record":"16\/07\/22 EBFM 11:00 E BFM 12,24 C-42 00-108","properties":{"date_departure_arrival":"16\/07\/22 EBFM 11:00 E BFM 12,24","aircraft":"C-42 00-108"}},{"record":"08\/10\/22 EBFN 13:30 EBFN 14:35 C-42\n00-108","properties":{"date_departure_arrival":"08\/10\/22 EBFN 13:30 EBFN 14:35","aircraft":"C-42\n00-108"}},{"record":"20\/06\/22 EBFN 14.00 EBFN 15.04 C-42 00-108","properties":{"date_departure_arrival":"20\/06\/22 EBFN 14.00 EBFN 15.04","aircraft":"C-42 00-108"}},{"record":"13\/11\/22 EBPN 12:30 E BTN 13:38 C-42 00-108","properties":{"date_departure_arrival":"13\/11\/22 EBPN 12:30 E BTN 13:38","aircraft":"C-42 00-108"}},{"record":"13\/11\/22 EBFN 16:00 EBFN 16:13 C-42 00-108","properties":{"date_departure_arrival":"13\/11\/22 EBFN 16:00 EBFN 16:13","aircraft":"C-42 00-108"}},{"record":"14\/08\/22 EBFN 12,30 EBAM 13:10 C-42 00-108","properties":{"date_departure_arrival":"14\/08\/22 EBFN 12,30 EBAM 13:10","aircraft":"C-42 00-108"}},{"record":"21\/08\/22 EBFN 12:00 EBFN 13:15 C-42 100-108","properties":{"date_departure_arrival":"21\/08\/22 EBFN 12:00 EBFN 13:15","aircraft":"C-42 100-108"}},{"record":"10\/07\/22 EBFN 16:00 EBFN 16.49 C-42 00-108","properties":{"date_departure_arrival":"10\/07\/22 EBFN 16:00 EBFN 16.49","aircraft":"C-42 00-108"}},{"record":"28\/08\/22 EBEN 11:00 EBFN 12:04 C-42\n00-108","properties":{"date_departure_arrival":"28\/08\/22 EBEN 11:00 EBFN 12:04","aircraft":"C-42\n00-108"}},{"record":"24\/07\/22 EBEN 09:30 EBFN 10,21 C-42 00-108","properties":{"date_departure_arrival":"24\/07\/22 EBEN 09:30 EBFN 10,21","aircraft":"C-42 00-108"}},{"record":"14\/08\/22 EBAM 14:00 EBFN 14:40 C-42 100-108","properties":{"date_departure_arrival":"14\/08\/22 EBAM 14:00 EBFN 14:40","aircraft":"C-42 100-108"}},{"record":"26\/11\/22 EBFN 13:00 EBFN 13:47 C-42 00-108","properties":{"date_departure_arrival":"26\/11\/22 EBFN 13:00 EBFN 13:47","aircraft":"C-42 00-108"}},{"record":"07\/08\/22 ERF 10uco EBFN 11: 2-42 00-108","properties":{"date_departure_arrival":"07\/08\/22 ERF 10uco EBFN 11:","aircraft":"2-42 00-108"}}]';
        return json_decode($string, true);
    }

    /**
     * Convert RepeatedField data to array
     *
     * @param RepeatedField $entities
     * @return array
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
}
