<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftRequest extends FormRequest
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
            'template_id'    => 'required|exists:gift_templates,id',
            'sender_name'     => 'required|string',
            'sender_email'    => 'required|email',
            'recipient_name'  => 'required|string',
            'recipient_email' => 'required|email',
            'price'           => 'required|numeric',
            'payment_type'    => 'required',
        ];
    }
}
