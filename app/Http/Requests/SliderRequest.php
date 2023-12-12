<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function rules(): array
    {
        return request()->isMethod('put') || request()->isMethod('patch') ? $this->onUpdate() : $this->onCreate();
    }

    public function onCreate(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'media_type' => ['required', 'in:image,video'],
            'media_path' => ['required'],
        ];
    }

    public function onUpdate(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'media_type' => ['sometimes', 'in:image,video'],
            'media_path' => ['sometimes'],
        ];
    }
}
