<?php

namespace App\Http\Controllers;

use App\Http\Resources\WidgetCategoryResource;
use App\Http\Resources\WidgetTemplateResource;
use App\Models\WidgetCategory;
use App\Models\WidgetTemplate;

class PageBuilderController extends Controller
{
    public function index()
    {
        $widgetCategories = WidgetCategory::all();
        $widgetTemplates = WidgetTemplate::with(['category', 'attributes'])->get();

        return [
            'widget_categories' => WidgetCategoryResource::collection($widgetCategories),
            'widget_templates' => WidgetTemplateResource::collection($widgetTemplates),
        ];
    }
}
