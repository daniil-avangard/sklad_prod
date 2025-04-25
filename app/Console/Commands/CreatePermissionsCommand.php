<?php

namespace App\Console\Commands;

use App\Enum\UserRoleEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CreatePermissionsCommand extends Command
{

    protected $signature = 'permissions:create';

    public function handle()
    {
        $this->warn('Установка полномочий...');

        // Устанавливает полномочия
        $this->createPolicyPermissions();

        // Создание ролей с правами
        $this->createRoleWithPermissions(UserRoleEnum::SUPER_ADMIN->label(), UserRoleEnum::SUPER_ADMIN->value, true, range(1001, 1061));
        $this->createRoleWithPermissions(UserRoleEnum::MANAGER->label(), UserRoleEnum::MANAGER->value, false, [1016, 1024, 1027]);
        $this->createRoleWithPermissions(UserRoleEnum::DIVISION_MANAGER->label(), UserRoleEnum::DIVISION_MANAGER->value, false, [1016, 1024, 1025, 1026, 1028, 1030, 1031]);
        $this->createRoleWithPermissions(UserRoleEnum::TOP_MANAGER->label(), UserRoleEnum::TOP_MANAGER->value, false, [1003, 1006, 1010, 1011, 1015, 1016, 1020, 1024, 1025, 1026, 1028, 1029, 1030, 1031, 1032, 1033, 1035, 1036, 1042, 1047, 1052, 1057]);
        $this->createRoleWithPermissions(UserRoleEnum::WAREHOUSEMAN->label(), UserRoleEnum::WAREHOUSEMAN->value, false, [1005, 1006, 1007, 1008, 1009, 1011, 1012, 1013, 1014, 1016, 1020, 1034, 1035, 1036, 1037, 1038, 1039]);

        $this->info('Полномочия установлены');

        return Command::SUCCESS;
    }

    private function createRoleWithPermissions(string $name, string $value, bool $super, array $permissions): void
    {
        // Создаем роль или получаем существующую
        $role = Role::query()->firstOrCreate([
            'name' => $name,
            'value' => $value,
            'super' => $super,
        ]);

        // Формируем записи для вставки в role_permission
        $rolePermissions = array_map(function ($permissionId) use ($role) {
            return [
                'role_id' => $role->id,
                'permission_id' => $permissionId,
            ];
        }, $permissions);

        // Вставляем записи в role_permission
        DB::table('role_permission')->insert($rolePermissions);

        $this->info("Роль \"$name\" и разрешения успешно созданы.");
    }

    private function createPolicyPermissions(): void
    {
        // Начальный ID для разрешений
        $permissionId = 1001;
        $policies = Gate::policies();
        $currentTime = Carbon::now();

        foreach ($policies as $model => $policy) {
            if (class_exists($policy)) {
                $methods = $this->getPolicyMethods($policy);
                $policyNames = $this->getPolicyNames($policy);

                foreach ($methods as $method) {
                    $permissionName = $policyNames[$method] ?? $method;

                    Permission::query()->insert([
                        'id' => $permissionId,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'name' => $permissionName,
                        'action' => $method,
                        'model' => $model,
                    ]);

                    $permissionId++;

                    // Permission::query()
                    //     ->firstOrCreate([
                    //         'name' => $permissionName,
                    //         'action' => $method,
                    //         'model' => $model,
                    //     ]);
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
