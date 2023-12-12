<?php

namespace App\Providers;

use App\Interfaces\CampaignInterface;
use App\Interfaces\InitRequestInterface;
use App\Interfaces\LinkInterface;
use App\Interfaces\NewsInterface;
use App\Interfaces\ProjectInterface;
use App\Interfaces\RegistrationInterface;
use App\Services\CampaignService;
use App\Services\InitRequestService;
use App\Services\LinkService;
use App\Services\NewsService;
use App\Services\ProjectService;
use App\Services\RegistrationService;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(RegistrationInterface::class, RegistrationService::class);
        $this->app->bind(ProjectInterface::class, ProjectService::class);
        $this->app->bind(NewsInterface::class, NewsService::class);
        $this->app->bind(InitRequestInterface::class, InitRequestService::class);
        $this->app->bind(LinkInterface::class, LinkService::class);
        $this->app->bind(CampaignInterface::class, CampaignService::class);

    }
}
