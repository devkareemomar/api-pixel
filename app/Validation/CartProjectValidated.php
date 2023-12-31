<?php

namespace App\Validation;

use App\Enums\RecurringEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CartProjectValidated extends FormRequest
{
    public function rules(): array
    {
        return [
            'project_id' => ['required'],
            'amount' => ['required'],
            'recurring' => ['nullable', new Enum(type: RecurringEnum::class)],
            'session_id' => ['nullable'],
            'gifted_to_email' => 'nullable|string|max:150',
            'gifted_to_phone' => 'nullable|string|max:25',
            'gifted_to_name' => 'nullable|string|max:150',
            'donor_comment' => 'nullable|string',
        ];
    }


}
