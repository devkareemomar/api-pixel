<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectAllResource;
use App\Http\Resources\ProjectBannerResource;
use App\Http\Resources\ProjectMenuResource;
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
        // $projects = $this->project->project($request);
        $currentDate = date('Y-m-d');
        $name = $request->input('name');
        $sort = $request->input('sort');
        $project_id = $request->input('project_id');


        $projects = Project::query()
            ->with(['category','languageProject'])
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate);

            if ($name) {
                $projects =  $projects->whereHas('languageProject', function ($query) use ($name){
                    $query->where('name', 'like', '%'.$name.'%');
                });
            }




            if ($sort) {
                $projects = $projects->orderByRaw('ISNULL(projects.order) '.$sort.', projects.order '.$sort.'');
            }else{
                $projects = $projects->orderByRaw('ISNULL(projects.order) asc, projects.order asc');
            }

            if ($project_id) {
                $projects =  $projects->where('id',$project_id);
            }

            $projects = $projects->paginate($request->input('per_page') ?? 9);
        return ProjectAllResource::collection($projects);
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
            ->with('category')
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')
            ->limit(12)->get();



        return ProjectAllResource::collection($projects);

    }

    public function bannerProjects()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->where('show_in_home_page',1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')->get();

        return ProjectBannerResource::collection($projects);

    }


    public function giftsProjects()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->where('is_gift',1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')->get();

        return ProjectAllResource::collection($projects);

    }
    public function menuProjects()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->where('show_in_menu',1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')->get();

        return ProjectMenuResource::collection($projects);

    }

    public function continuousProjects()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->where('active','=','1')
            ->where('is_continuous',1)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')->get();

        return ProjectMenuResource::collection($projects);

    }

    public function stories()
    {
        $currentDate = date('Y-m-d');

        $projects = Project::query()
            ->select('projects.id', 'projects.name', 'thumbnail')
            ->with('images')
            // ->whereDate('start_date', '<=', $currentDate)
            // ->whereDate('end_date', '>=', $currentDate)
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
