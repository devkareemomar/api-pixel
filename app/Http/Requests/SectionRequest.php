<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page_id' => ['required', 'exists:pages,id'],
            'order' => [
                'required',
                Rule::unique('sections')->where(function ($query) {
                    return $query->where('page_id', $this->input('page_id'));
                })->ignore($this->route('section'))
            ]
        ];
    }
}
