<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\Page;
use App\Models\Project;
use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VisitJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $routeName, protected $visitableId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $userId = auth()->user()?->id;
        $ipInfo = geoip(request()->ip());
        $visitable_id = $this->getVisitableId();
        $visitable_type = $this->getVisitableType();

        if (!$visitable_id || !$visitable_type) {
            return;
        }

        Visit::updateOrCreate(
            [
            'visitable_id' => $visitable_id,
            'visitable_type' => $visitable_type,
            'user_id' => $userId,
            'ip' => request()->ip(),
        ],
            [
                'updated_at' => now(),
                'country' => $ipInfo?->country ?? null,
                'country_code' => $ipInfo?->iso_code ?? null
            ]
        );
    }

    private function getVisitableType()
    {

        return match ($this->routeName) {
            'pages.show' => Page::class,
            'news.show' => News::class,
            'projects.show' => Project::class,
            default => null,
        };
    }

    private function getVisitableId()
    {

        $project_id = Project::where('slug', $this->visitableId)
            ->orWhere('sku', $this->visitableId)->first()?->id;

        return match ($this->routeName) {
            'pages.show' => $this->visitableId,
            'news.show' => $this->visitableId,
            'projects.show' => $project_id,
            default => null,
        };
    }
}
