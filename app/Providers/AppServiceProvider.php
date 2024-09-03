<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Permission;
use App\Models\Arival;
use App\Policies\ArivalPolicy;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(BreadcrumbsServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // require base_path('routes/breadcrumbs.php');

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Arival::class, ArivalPolicy::class);

    }
}
