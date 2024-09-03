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

    protected $policies = [
        User::class => UserPolicy::class,
        Arival::class => ArivalPolicy::class,
    ];

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        if ($this->app->runningInConsole()) {
            return;
        }

        Gate::before(function (User $user) {
            return $user->roles
                ->where('super', true)
                ->isNotEmpty() ?: null;
        });

        $permissions = Permission::query()
            ->whereNull('model')
            ->get();

        foreach ($permissions as $permission) {
            Gate::define($permission->action, 
                function (User $user) use ($permission) {
                    return $user->permissions
                        ->contains('id', $permission->id);
            });
        }

    }
}
