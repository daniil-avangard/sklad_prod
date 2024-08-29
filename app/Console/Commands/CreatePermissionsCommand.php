<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
class CreatePermissionsCommand extends Command
{

    protected $signature = 'permission:create';


    protected $description = 'Command description';


    public function handle()
    {
        $this->createPermission();

        $this->info('Полномочия успешно созданы');
        

        return self::SUCCESS;
    }

    private function createPermission()
    {
        $policies = Gate::policies();

        foreach ($policies as $model => $policy) {
            $methods = $this->getPolicyMethods($policy);
        }

        foreach ($methods as $method) {
            Permission::query()
            ->firstOrCreate([
                'action' => $method,
                'model' => $model,
            ]);
        }
    }

    private function getPolicyMethods(string $policy)
    {
        $methods = get_class_methods($policy);

        return array_filter($methods, function (string $method) {
            return !in_array($method,[
                'denyWithStatus',
                'denyWithMessage',
                'denyAsNotFound',
                'denyAsForbidden',
                'denyAsTooManyRequests',
                'denyAsUnauthorized',
                'denyAsBadRequest',
                'denyAsInternalServerError',
                'denyAsServiceUnavailable',
            ]);
        });
    }
}
