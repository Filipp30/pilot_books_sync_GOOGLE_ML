<?php

namespace App\Http\Requests;

use App\Models\Enums\BookTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookAddRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => ['required', 'file'],
            'type' => ['required', Rule::in(BookTypes::values())],
        ];
    }
}
