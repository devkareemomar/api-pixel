<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormBuilderRequest;
use App\Http\Resources\FormResource;
use App\Http\Resources\FormResourceBrief;
use App\Http\Resources\FormStatusResource;
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

    public function getListDataForm()
    {
        $forms=FormBuilder::where('active',1)->get();

        return  FormResourceBrief::collection($forms);
    }

    public function setDataForm(FormBuilderRequest $request, $form_id)
    {
        $form_data= $request->validated();
        if (FormBuilderData::where('form_builder_id',$form_id)->where('national_id',$form_data['national_id'])->first()){
            return $this->return_fail(__('This National ID already exists'), []);
        }
        foreach($form_data['data']  as $key => $data){
            if($data['type'] == 'file'){
                $form_data['data'][$key]['value'] = $this->handleFileUpload($data['value'], 'case_files');
            }
        }
        $form=FormBuilderData::create([
            'form_builder_id'=>$form_id,
            'national_id'=>$form_data['national_id'],
            'data'=> json_encode($form_data['data']),
            'status'=>"Under review",
        ]);
        return response()->json( ["order_number"=>$form->id]);
    }


    public function getFormStatus($order_number)
    {

        $form = FormBuilderData::where('id',$order_number)->first();
        return  FormStatusResource::make($form);
    }

    public function handleFileUpload($file, $storagePath)
    {
        if (isset($file)) {
            return $file->store($storagePath, 'public');
        }

        return null;
    }
}
