<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
class UserPolicy
{
    use HandlesAuthorization;



    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('view', User::class);
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('create', User::class);
    }

 
    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('update', User::class);
    }

  
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('delete', User::class);
    }

}
