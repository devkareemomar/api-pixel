<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormBuilderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'national_id' => ['required', 'numeric'],
            'data' => ['required', 'json'],
        ];
    }
}
