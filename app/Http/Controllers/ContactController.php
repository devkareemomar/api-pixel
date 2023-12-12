<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends BaseApiController
{
    public function store(ContactRequest $request)
    {
        Contact::create($request->validated());
        return $this->return_success(__('Contact added successfully'));
    }
}
