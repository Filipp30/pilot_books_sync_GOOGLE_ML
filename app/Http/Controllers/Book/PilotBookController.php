<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookAddRequest;
use App\Services\GoogleCloud\DocumentAiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PilotBookController extends Controller
{
    public function add(BookAddRequest $request, DocumentAiService $aiService): Response
    {
        $validated = $request->validated();

        $result = $aiService->handlePilotBookDocument();
        dd($result);

        //TODO: save data for the given type ULM - PPL
        //TODO: create repository for each type

        return response([
            'message'=>'Your document will be handled. You receive notification when finish.'
        ],200);
    }
}
