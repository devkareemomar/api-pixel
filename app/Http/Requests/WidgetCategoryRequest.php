<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'string', 'max:255'],
            'folder_name' => ['required', 'string', 'max:255'],
        ];
    }
}
