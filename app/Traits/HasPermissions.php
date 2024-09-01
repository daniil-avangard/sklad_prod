<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Permission;
use App\Models\Role;



trait HasPermissions

{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission(string $action, string $model): bool
    {
        return $this->hasDirectPermission($action, $model)
            || $this->hasRolePermissions($action, $model);
    }

    public function hasDirectPermission(string $action, string $model): bool
    {
        return $this->permissions
            ->where('action', $action)
            ->where('model', $model)
            ->isNotEmpty();
    }

    public function hasRolePermissions(string $action, string $model): bool
    {
        $this->roles->loadMissing('permissions');

        foreach ($this->roles as $role) {
            $exists = $role->permissions
                ->where('action', $action)
                ->where('model', $model)
                ->isNotEmpty();

            if ($exists) {
                return true;
            }
        }

        return false;
    }
}