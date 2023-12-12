<?php

namespace App\Http\Controllers;


use App\Http\Requests\SectionRequest;
use App\Models\Section;

class SectionController extends BaseApiController
{
    public function store(SectionRequest $request){
        Section::create($request->validated());
        return $this->return_success(__('Section added successfully'));
    }

    public function update(SectionRequest $request, Section $section){
        $section->update($request->validated());
        return $this->return_success(__('Section updated successfully'));
    }

    public function destroy(Section $section){
        $section->delete();
        return $this->return_success(__('Section deleted successfully'));
    }

}
