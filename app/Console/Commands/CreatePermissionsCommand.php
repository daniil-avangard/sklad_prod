<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;
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
            if (class_exists($policy)) { 
                $methods = $this->getPolicyMethods($policy); 
                $policyNames = $this->getPolicyNames($policy); 
                foreach ($methods as $method) {
                    $permissionName = $policyNames[$method] ?? $method;
                    Permission::query()
                        ->firstOrCreate([
                            'name' => $permissionName,
                            'action' => $method,
                            'model' => $model,
                        ]);
                }
            }
        }
    }
    
    private function getPolicyMethods(string $policy)
    {
        $methods = get_class_methods($policy); 
    
        return array_filter($methods, function (string $method) {
            return !in_array($method, [
                'denyWithStatus',
                'denyAsNotFound',
            ]);
        });
    }
    
    private function getPolicyNames(string $policy): array
    {
        $reflection = new \ReflectionClass($policy);
        $docComment = $reflection->getDocComment();
    
        if ($docComment) {
            preg_match('/@PolicyName\((.*?)\)/', $docComment, $matches);
            if (isset($matches[1])) {
                $policyNamesString = $matches[1];
                $policyNamesArray = [];
                preg_match_all('/(\w+)="([^"]+)"/', $policyNamesString, $nameMatches, PREG_SET_ORDER);
                foreach ($nameMatches as $nameMatch) {
                    $policyNamesArray[$nameMatch[1]] = $nameMatch[2];
                }
                return $policyNamesArray;
            }
        }
    
        return [];
    }

}
