<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
class UserPolicy

/**
 * @PolicyName(view="Просмотр пользователей", create="Создание пользователей", update="Редактирование пользователей", delete="Удаление пользователей")
 */
{
    use HandlesAuthorization;

    public function view(User $user, User $model = null): bool
    {
        return $user->hasPermission('view', User::class);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create', User::class);
    }

    public function update(User $user, User $model = null): bool
    {
        return $user->hasPermission('update', User::class);
    }

    public function delete(User $user, User $model = null): bool
    {
        return $user->hasPermission('delete', User::class);
    }
}