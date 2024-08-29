<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Permission;


trait HasPermissions

{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission(string $action, string $model): bool
    {
        return $this->permissions
        ->where('action', $action)
        ->where('model', $model)
        ->isNotEmpty();
    }
}