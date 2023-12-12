<?php

namespace App\Http\Controllers;

use App\Http\Requests\WidgetCategoryRequest;
use App\Models\WidgetCategory;
use Illuminate\Http\Request;

class WidgetCategoryController extends BaseApiController
{
    public function index(Request $request)
    {
        return $this->return_success(__('Widget Categories retrieved successfully'),WidgetCategory::all());
    }

    public function store(WidgetCategoryRequest $request)
    {
        WidgetCategory::create($request->validated());
        return $this->return_success(__('Widget Category added successfully'));
    }

    public function update(WidgetCategoryRequest $request, WidgetCategory $widgetCategory)
    {
        $widgetCategory->update($request->validated());
        return $this->return_success(__('Widget Category updated successfully'));
    }

    public function destroy(WidgetCategory $widgetCategory)
    {
        $widgetCategory->delete();
        return $this->return_success(__('Widget Category deleted successfully'));
    }
}
