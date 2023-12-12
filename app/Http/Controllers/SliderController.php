<?php

namespace App\Http\Controllers;

use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends BaseApiController
{
    public function index(Request $request)
    {
        return $this->return_success(__('Sliders retrieved successfully'), Slider::all());
    }

    public function store(SliderRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('media_path')) {
            $data['media_path'] = $request->file('media_path')->store('media_paths', 'public');
        }
        Slider::create($data);
        return $this->return_success(__('Slider Category added successfully'));
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        $data = $request->validated();
        if ($request->hasFile('media_path')) {
            $data['media_path'] = $request->file('media_path')->store('media_paths', 'public');
        }
        $slider->update($data);
        return $this->return_success(__('Slider Category updated successfully'));
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return $this->return_success(__('Slider Category deleted successfully'));
    }
}
