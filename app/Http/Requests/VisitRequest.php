<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'session_id' => ['nullable','string'],
            'user_id' => ['nullable','integer'],
            'project_id' => ['nullable','integer'],
            'article_id' => ['nullable','integer'],
            'page_id' => ['nullable','integer'],
        ];
    }
}
