<?php

namespace App\Services;

use App\Interfaces\ProjectInterface;
use App\Models\LanguageProject;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectInterface
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project->filter()->sort()
            ->with('project_gallery');
    }


    public function project($request)
    {
        $currentDate = date('Y-m-d');

        return $this->project
            ->with(['languageProject', 'images'])
            ->select(
                'projects.id',
                'projects.donation_available',
                'language_project.name',
                'language_project.slug',
                'sku',
                'language_project.short_description',
                'language_project.description',
                'total_earned',
                'total_wanted',
                'show_in_home_page',
                'projects.created_at',
                'thumbnail',
                'projects.name AS project_name',
                'projects.slug AS project_slug',
                'projects.short_description AS project_short_description',
                'projects.description AS project_description',
                'project_status_id',
                'category_id',
                'sub_category_id',
                'is_gift',
                'is_zakat',
                'is_stock',
                'is_quick_donation',
                'unit_value',
                'stock',
                'show_timer',
                'show_target_amount',
                'show_paid_amount',
                'show_percentage',
                'main_image',
                'creator_id',
                'video',
                'is_continuous',
                'is_full_unit'

            )
            // I don't know who make visibility 0 is public ?!
            ->where('visibility', '=', '0')
            ->where('hidden', '=', '0')
            ->join('language_project', 'language_project.project_id', '=', 'projects.id')
            ->where('language_project.lang_code', config('app.locale'))
            // ->whereDate('start_date', '<=', $currentDate)
            // ->whereDate('end_date', '>=', $currentDate)
            ->where('active','=','1')
            ->orderByRaw('ISNULL(projects.order) asc, projects.order asc')
            ->orderBy('projects.updated_at', 'desc')
            ->paginate($request->input('per_page') ?? 6);
    }

    public function project_details($request, $project_id)
    {
        $data = [];
        $languageProject = LanguageProject::whereSlug($project_id)->first();
        $project_data = $this->project->where('visibility', '=', '0')
            ->select(
                'projects.id',
                'projects.donation_available',
                'language_project.name',
                'sku',
                'language_project.slug',
                'featured',
                'thumbnail',
                'start_date',
                'end_date',
                'accept_donation',
                'donor_name_required',
                'donor_phone_required',
                'show_in_home_page',
                'show_in_shop',
                'is_gift',
                'language_project.short_description',
                'language_project.description',
                'total_earned',
                'total_wanted',
                'projects.created_at',
                'projects.name AS project_name',
                'projects.slug AS project_slug',
                'projects.short_description AS project_short_description',
                'projects.description AS project_description',
                'project_status_id',
                'category_id',
                'sub_category_id',
                'is_gift',
                'is_zakat',
                'is_stock',
                'is_quick_donation',
                'unit_value',
                'stock',
                'show_timer',
                'show_target_amount',
                'show_paid_amount',
                'show_percentage',
                'main_image',
                'show_donor_name',
                'show_donor_phone',
                'creator_id',
                'suggested_values',
                'video',
                'show_donation_comment',
                'donation_comment',
                'is_continuous',
                'is_full_unit',
            )
            ->where('projects.id', $languageProject?->project?->id)
            ->join('language_project', 'language_project.project_id', '=', 'projects.id')
            ->where('language_project.lang_code', config('app.locale'))
            ->with(['review' => function ($query) {
                $query->select('comment', 'project_id', 'users.name as username', 'reviews.created_at')
                    ->leftJoin('users', function ($join) {
                        $join->on('users.id', '=', 'reviews.user_id');
                    });
            }])
            ->first();
        //            ->with(['albums' => function ($query) {
        //                $query->select('title', 'description',
        //                    DB::raw('GROUP_CONCAT( media.image) AS images'),
        //                    DB::raw('GROUP_CONCAT(DISTINCT media.video) AS videos'))
        //                    ->leftJoin('album_media', 'albums.id', '=', 'album_media.album_id')
        //                    ->leftJoin('media', 'album_media.media_id', '=', 'media.id')
        //                    ->groupBy('albums.id');
        //            }])->first();
        if (empty($project_data)) {
            return $data;
        }
        return $project_data;
    }
}
