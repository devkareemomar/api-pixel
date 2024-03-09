<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'project_id'      => 'required|exists:projects,id',
            'amount'          => 'required|numeric',
            'payment_type'    => 'required',
            'user_id'         => 'nullable',
        ];
    }
}
