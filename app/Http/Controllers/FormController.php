<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormBuilderRequest;
use App\Http\Resources\FormResource;
use App\Http\Resources\FormResourceBrief;
use App\Http\Resources\FormStatusResource;
use App\Models\FormBuilder;
use App\Models\FormBuilderData;

class FormController extends BaseApiController
{
    public function getDataForm($form_id)
    {
        $form = FormBuilder::findOrFail($form_id);

        return  FormResource::make($form);
    }

    public function getListDataForm()
    {
        $forms = FormBuilder::where('active', 1)->get();

        return  FormResourceBrief::collection($forms);
    }

    public function setDataForm(FormBuilderRequest $request, $form_id)
    {
        $form_data = $request->validated();
        if (FormBuilderData::where('form_builder_id', $form_id)->where('national_id', $form_data['national_id'])->first()) {
            return $this->return_fail(__('This National ID already exists'), []);
        }

        foreach ($form_data['data']  as $key => $data) {
            if ($data['type'] == 'file') {
                $form_data['data'][$key]['value'] = $this->handleFileUpload($data['value'], 'case_files');
            }
        }
        $form = FormBuilderData::create([
            'form_builder_id' => $form_id,
            'national_id' => $form_data['national_id'],
            'data' => json_encode($form_data['data']),
            'status' => "Under review",
        ]);
        return response()->json(["data" => ["message" => __('Case Create successfully'),"order_number" => $form->id]]);
    }


    public function getFormStatus($order_number)
    {
        $form = FormBuilderData::where('id', $order_number)->first();
        return  FormStatusResource::make($form);
    }

    public function getFormStatusList($national_id)
    {
        $formsData = FormBuilderData::with('form')->where('national_id', $national_id)->get();

        return  FormStatusResource::collection($formsData);
    }

    public function handleFileUpload($file, $storagePath)
    {
        if (isset($file)) {
            $imageData = base64_decode($file);

            $prefix = date('YmdHis'); // You can customize the prefix if needed
            $uniqueId = uniqid();
            $randomString = md5($uniqueId . rand(0, 1000)); // Adding some randomness

            $filename = $prefix . '_' . $randomString . '.png';

            // Ensure the directory exists before attempting to save the file
            $directory = storage_path($storagePath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true); // create directory with permission 0755
            }

            file_put_contents("$directory/$filename", $imageData);

            return "storage/$storagePath/$filename";
        }

        return null;
    }
}
