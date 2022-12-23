<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookAddRequest;
use App\Jobs\ProcessPilotbookJob;
use App\Models\Enums\BookTypes;
use Illuminate\Http\File;
use Illuminate\Http\Response;

class PilotBookController extends Controller
{
    public function add(BookAddRequest $request): Response
    {
        $validated = $request->validated();

        // TODO: Check if document is valid before dispatch a job
        $pdf = new File('/Users/dev/Desktop/_/pilot_books_sync/app/Services/GoogleCloud/pilot_book.pdf');

        ProcessPilotbookJob::dispatchSync(BookTypes::ULM(), null);

        return response([
            'message'=>'Your document will be handled. You receive notification when finish.'
        ],200);
    }
}
