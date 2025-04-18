<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Permission;
use App\Models\Arival;
use App\Models\Category;
use App\Models\Company;
use App\Models\Division;
use App\Models\DivisionGroup;
use App\Models\Korobka;
use App\Policies\ArivalPolicy;
use App\Models\Writeoff;
use App\Policies\WriteoffPolicy;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Models\ProductVariant;
use App\Policies\ProductVariantPolicy;
use App\Models\Order;
use App\Policies\CategoryPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\DivisionGroupPolicy;
use App\Policies\DivisionPolicy;
use App\Policies\KorobkaPolicy;
use App\Policies\OrderPolicy;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BreadcrumbsServiceProvider::class);
    }

    protected $policies = [
        User::class => UserPolicy::class,
        Arival::class => ArivalPolicy::class,
        Writeoff::class => WriteoffPolicy::class,
        Product::class => ProductPolicy::class,
        ProductVariant::class => ProductVariantPolicy::class,
        Order::class => OrderPolicy::class,
        Korobka::class => KorobkaPolicy::class,
        Category::class => CategoryPolicy::class,
        Company::class => CompanyPolicy::class,
        Division::class => DivisionPolicy::class,
        DivisionGroup::class => DivisionGroupPolicy::class,
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
            Gate::define(
                $permission->action,
                function (User $user) use ($permission) {
                    return $user->permissions
                        ->contains('id', $permission->id);
                }
            );
        }
    }
}
