<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\GiftRequest;
use App\Models\Gift;
use App\Models\GiftTemplate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GiftTemplateResource;

class GiftController extends BaseApiController
{
    public function index()
    {
        return $this->return_success(__('Gifts retrieved successfully'), Gift::filter()->get());
    }

    public function store(GiftRequest $request)
    {
        $data = $request->validated();
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
            $data['sender_name'] = auth()->user()->name;
            $data['sender_email'] = auth()->user()->email;
        } else {
            Validator::make($data, [
                'sender_name' => 'required|string',
                'sender_email' => 'required|email',
            ])->validate();
        }

        $gift = Gift::create($data);
        return $this->return_success(__('Gift added successfully'), $gift);
    }


    public function  gifts_templates()
    {

        $templates  =  GiftTemplate::get();
        return GiftTemplateResource::collection($templates);
    }
}
