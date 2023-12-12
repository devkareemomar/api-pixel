<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visit extends Model
{
    use HasFactory;

    protected $table = 'visitables';

    protected $fillable = [
        'user_id', 'visitable_id', 'visitable_type', 'ip', 'country', 'country_code', 'updated_at'
    ];

    public static function getPagesWithVisitDetails()
    {
        $activities = static::select(
            'visitable_id',
            'visitable_type',
            DB::raw('count(*) as total_visits'),
            DB::raw('max(updated_at) as last_visit_date')
        )
            ->with('visitable:id,name')
            ->groupBy('visitable_id', 'visitable_type')
            ->orderByDesc('total_visits')
            ->get();

        $activities = $activities->map(function ($activity) {

            $lastVisit = static::select('visitables.id as id', 'user_id', 'users.name as user_name', 'users.email as user_email', 'country', 'country_code', 'ip')->where('visitable_id', $activity->visitable_id)
                ->where('visitable_type', $activity->visitable_type)
                ->orderByDesc('visitables.updated_at')
                ->leftJoin('users', 'visitables.user_id', 'users.id')
                ->first();

            $activity->last_visit_name = $lastVisit?->user_name ?: 'Guest';
            $activity->last_visit_email = $lastVisit?->user_email ?: 'Guest';
            $activity->last_visitor_ip = $lastVisit ? $lastVisit->ip : null;
            $activity->type = class_basename($activity->visitable_type);
            $activity->page_name = $activity->visitable->name ?? $activity->visitable->title ?? 'Unknown';
            $activity->country = $lastVisit?->country ?: 'Unknown';
            $activity->country_code = $lastVisit?->country_code ?: '';
            $activity->user_id = $lastVisit->user_id;
            $activity->id = $lastVisit->id;


            return $activity;
        });

        return $activities;

    }

    public static function getPageWithVisitDetails($visit_id)
    {
        $visit = static::find($visit_id);
        $activities = static::select(
            'users.name as username',
            'visitable_id',
            'visitable_type',
            'visitables.updated_at',
            'ip',
            'country',
            'country_code'
        )
            ->with('visitable:id,name')
            ->where('visitable_id', $visit->visitable_id)
            ->where('visitable_type', $visit->visitable_type)
            ->leftJoin('users', 'visitables.user_id', 'users.id')
            ->orderByDesc('updated_at')
            ->get();

        $activities = $activities->map(function ($activity) {
            $activity->last_visitor_ip = $activity->ip;
            $activity->type = class_basename($activity->visitable_type);
            $activity->page_name = $activity->visitable->name ?? $activity->visitable->title ?? 'Unknown';

            return $activity;
        });

        return $activities;
    }

    public static function getUserActivities($userId)
    {
        $user = User::find($userId);
        $activities = static::select(
            'visitable_id',
            'visitable_type',
            'updated_at',
            'ip',
            'country',
            'country_code'
        )
            ->with('visitable:id,name')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();

        $activities = $activities->map(function ($activity) {
            $activity->type = class_basename($activity->visitable_type);
            $activity->page_name = $activity->visitable->name ?? $activity->visitable->title ?? 'Unknown';
            $activity->country = $activity?->country ?: 'Unknown';
            $activity->country_code = $activity?->country_code ?: '';


            return $activity;
        });

        return $activities;
    }


    public function visitable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
