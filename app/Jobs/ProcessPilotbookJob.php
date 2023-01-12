<?php

namespace App\Jobs;

use App\Exceptions\ProcessingPilotbookException;
use App\Models\Enums\BookTypes;
use App\Repository\Services\UlmBookRepository;
use App\Services\Contracts\DocumentHandlerContract;
use App\Traits\CollectionHasInstanceOf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessPilotbookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CollectionHasInstanceOf;

    private BookTypes $type;
    private string $fileName;

    public function __construct(BookTypes $type, string $fileName)
    {
        $this->type = $type;
        $this->fileName = $fileName;
    }

    /**
     * @throws ProcessingPilotbookException
     */
    public function handle(
        DocumentHandlerContract $documentAiService,
        UlmBookRepository $repository,
    ): void {
        if (Storage::disk('local')->missing($this->fileName)) {
            throw new ProcessingPilotbookException('Storage::disk', 'Missing file: '. $this->fileName);
        }

        $uploadedFile = new UploadedFile(storage_path('/app/'. $this->fileName), $this->fileName);

        try {
            $data = $documentAiService->handle($uploadedFile);

            if ($data->isNotEmpty()) {
                $repository::saveFromCollection($data);
            }

            Log::channel('development')->info('success: ProcessPilotbookJob is processed.', ['tenant_id' => tenant()->getAttribute('id')]);
        } catch (Throwable $e) {
            Log::channel('development')->critical('fail: ProcessPilotbookJob.', [
                'tenant_id' => tenant()->getAttribute('id'),
                'exception' => $e
            ]);
        } finally {
            Storage::disk('local')->delete($this->fileName);
        }
    }

    /**
     * @throws ProcessingPilotbookException
     */
    public function fail(Throwable $exception)
    {
        Log::channel('development')->info('fail: ProcessPilotbookJob', ['tenant_id' => tenant()->getAttribute('id')]);

        throw new ProcessingPilotbookException(
            'ProcessPilotbookJob Fail.',
            $exception->getMessage()
        );
    }
}
