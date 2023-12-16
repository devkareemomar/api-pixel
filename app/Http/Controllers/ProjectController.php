<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\ProjectResource;
use App\Interfaces\ProjectInterface;
use App\Models\LanguageProject;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends BaseApiController
{
    private $project;

    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    public function project(Request $request)
    {
        $projects = $this->project->project($request);
        return ProjectResource::collection($projects);
    }

    public function project_details(Request $request, $id)
    {
        $project = $this->project->project_details($request, $id);
        if (empty($project)) {
            return $this->return_fail(__('project.project_fail'), []);
        }
        $project->visit($request->user()?->id);
        return ProjectResource::make($project);
    }

    public function search(Request $request)
    {
        $languageProjects = collect([]);
        $keyword = $request->input('q');
        if ($keyword) {
            $languageProjects = LanguageProject::query()
                ->select('project_id')
                ->where('language_project.name', 'like', '%' . $keyword . '%')
                ->orWhere('language_project.description', 'like', '%' . $keyword . '%')
                ->orWhere('language_project.short_description', 'like', '%' . $keyword . '%')
                ->groupBy('project_id')
                ->pluck('project_id');
        }

        $currentDate = date('Y-m-d');

        $projects = Project::with('languageProject')
            ->select('projects.*')
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')

            ->join('language_project', 'language_project.project_id', '=', 'projects.id')
            ->where('language_project.lang_code', config('app.locale'))
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->whereIn('language_project.project_id', $languageProjects->toArray())
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')
            ->get();
        return ProjectResource::collection($projects);
    }

    public function allProjects()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->select('projects.name', 'projects.id','projects.slug')
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            // ->whereDate('start_date', '<=', $currentDate)
            // ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')
            ->get();

        $data = [];
        foreach ($projects as $project) {
            $data[] = ['id' => $project->id,'slug' => $project->slug, 'name' => $project->name];
        }

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function stories()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->select('projects.id', 'projects.name', 'thumbnail')
            ->with('images')
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->where('highlighted', true)
            ->get();

        $data = [];
        foreach ($projects as $project) {
            $data[] = ['id' => $project->id, 'name' => $project->name,
           'thumbnail' => $project->thumbnail ? config('app.dashboard') . $project->thumbnail : null,
                'images' => ImageResource::collection($project->images)];
        }


        return response()->json([
            'data' => $data,
        ], 200);
    }
}
