<?php

namespace App\Providers;

use App\Interfaces\CartServiceInterface;
use App\Interfaces\NewsInterface;
use App\Interfaces\ProjectInterface;
use App\Interfaces\RegistrationInterface;
use App\Services\CartService;
use App\Services\NewsService;
use App\Services\ProjectService;
use App\Services\RegistrationService;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        $this->app->bind(RegistrationInterface::class, RegistrationService::class);
        $this->app->bind(ProjectInterface::class, ProjectService::class);
        $this->app->bind(NewsInterface::class, NewsService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);

    }
}
