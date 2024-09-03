<?php

namespace App\Policies;

use App\Models\Arival;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
/**
 * @PolicyName(viewAny="Просмотр всех прибытий", view="Просмотр прибытия", create="Создание прибытия", update="Редактирование прибытия", delete="Удаление прибытия", changeStatus="Изменение статуса прибытия")
 */
class ArivalPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('viewAny', Arival::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Arival $arival): bool
    {
        return $user->hasPermission('view', Arival::class);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create', Arival::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Arival $arival): bool
    {
        return $user->hasPermission('update', Arival::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Arival $arival): bool
    {
        return $user->hasPermission('delete', Arival::class);
    }

    public function changeStatus(User $user, Arival $arival): bool
    {
        return $user->hasPermission('changeStatus', Arival::class);
    }
}
