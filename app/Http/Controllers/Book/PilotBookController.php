<?php

namespace App\Http\Controllers\Book;

use App\Exceptions\ProcessingPilotbookException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookAddRequest;
use App\Http\Requests\BookGetRequest;
use App\Models\Enums\BookTypes;
use App\Repository\Services\UlmBookRepository;
use App\Services\Contracts\DocumentHandlerContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Throwable;

class PilotBookController extends Controller
{
    /**
     * @throws ProcessingPilotbookException
     */
    public function add(BookAddRequest $request, DocumentHandlerContract $documentAiService): JsonResponse
    {
        // TODO: Check if document is valid before dispatch a job

        $validated = $request->validated();
        assert($validated['file'] instanceof UploadedFile);
        assert(BookTypes::instanceFromKey($validated['type']) instanceof BookTypes);

        try {
            $data = $documentAiService->handle($validated['file']);
        } catch (Throwable $e) {
            throw new ProcessingPilotbookException('Service error', $e);
        }

        if ($data->isNotEmpty()) {
            UlmBookRepository::saveFromCollection($data);
        }

        return response()->json([
            'message'=>'Your document is successfully handled',
            'records_added' => $data->count(),
        ]);
    }

    public function getAll(BookGetRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = UlmBookRepository::getAll(BookTypes::instanceFromKey($validated['type']));

        return response()->json([
            'data' => $data
        ]);
    }
}
