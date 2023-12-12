<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitRequest;
use App\Models\News;
use App\Models\Page;
use App\Models\Project;

class VisitController extends BaseApiController
{
    public function visit(VisitRequest $request)
    {
        $data = $request->validated();

        if (isset($data['project_id'])) {
            Project::findOrFail($data['project_id'])->visit($data['user_id']);
        } elseif (isset($data['article_id'])) {
            News::findOrFail($data['article_id'])->visit($data['user_id']);
        } elseif (isset($data['page_id'])) {
            Page::findOrFail($data['page_id'])->visit($data['user_id']);
        }

        return $this->return_success(__('Visit tracked successfully'));

    }
}
