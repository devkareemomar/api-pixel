<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormBuilderRequest;
use App\Http\Resources\FormResource;
use App\Models\FormBuilder;
use App\Models\FormBuilderData;
use Illuminate\Http\Request;

class FormController extends BaseApiController
{
    public function getDataForm($form_id)
    {
        $form=FormBuilder::findOrFail($form_id);

        return  FormResource::make($form);
    }

    public function setDataForm(FormBuilderRequest $request, $form_id)
    {
        $form_data= $request->validated();
        if (FormBuilderData::where('form_builder_id',$form_id)->where('national_id',$form_data['national_id'])->first()){
            return $this->return_fail(__('This National ID already exists'), []);
        }
        $form=FormBuilderData::create([
            'form_builder_id'=>$form_id,
            'national_id'=>$form_data['national_id'],
            'data'=>$form_data['data'],
            'status'=>"Under review",
        ]);
        return response()->json( ["order_number"=>$form->id]);
    }


    public function getFormStatus($order_number)
    {
        return FormBuilderData::where('id',$order_number)->first()->pluck('status');
    }
}
