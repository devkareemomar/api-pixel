<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WidgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'section_id' => 'required|exists:sections,id',
            'widget_category_id' => 'required|exists:widget_categories,id',
            'name' => 'required|string',
            'thumbnail' => 'required|string',
            'payload' => 'required|array',
            'order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('widgets')->where(function ($query) {
                    return $query->where('section_id', $this->input('section_id'));
                })->ignore($this->input('widget')),
            ],
            'size_percentage' => 'required|numeric|min:0|max:100'
        ];
    }
}
