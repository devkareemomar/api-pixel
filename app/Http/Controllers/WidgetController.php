<?php

namespace App\Http\Controllers;

use App\Http\Requests\WidgetRequest;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends BaseApiController
{
    public function store(WidgetRequest $request)
    {
        $data = $request->validated();
        $data['payload'] = json_encode($data['payload']);
        Widget::create($data);
        return $this->return_success(__('Widget added successfully'));
    }

    public function destroy(string $id)
    {
        Widget::destroy($id);
        return $this->return_success(__('Widget deleted successfully'));
    }
}
