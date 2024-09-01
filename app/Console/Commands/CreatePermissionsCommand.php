<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\Role;
class CreatePermissionsCommand extends Command
{

    protected $signature = 'permissions:create';

    public function handle()
    {
        $this->warn('Установка полномочий...');

        $this->createRoles();

        $this->createDefaultPermissions();

        $this->createPolicyPermissions();

        $this->info('Полномочия установлены');

        return Command::SUCCESS;
    }

    private function createRoles(): void
    {
        Role::query()->firstOrCreate([
            'name' => 'Супер админ',
            'super' => true,
        ]);
    }

    private function createDefaultPermissions(): void
    {
        // Permission::query()->firstOrCreate([
        //     'action' => 'logs:view',
        // ]);

        // Permission::query()->firstOrCreate([
        //     'action' => 'logs:delete',
        // ]);
    }

    private function createPolicyPermissions(): void
    {
        $policies = Gate::policies();

        foreach ($policies as $model => $policy) {
                $methods = $this->getPolicyMethods($policy);

            
                

            foreach ($methods as $method) {
                Permission::query()
                    ->firstOrCreate([
                        'action' => $method,
                        'model' => $model,
                    ]);
            }
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
