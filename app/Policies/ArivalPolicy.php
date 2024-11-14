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

    public function viewAny(User $user, Arival $arival = null): bool
    {
        return $user->hasPermission('viewAny', Arival::class);
    }


    public function view(User $user, Arival $arival = null): bool
    {
        return $user->hasPermission('view', Arival::class);
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('create', Arival::class);
    }

    public function update(User $user, Arival $arival = null): bool
    {
        return $user->hasPermission('update', Arival::class);
    }


    public function delete(User $user, Arival $arival = null): bool
    {
        return $user->hasPermission('delete', Arival::class);
    }

    public function changeStatus(User $user, Arival $arival = null): bool
    {
        return $user->hasPermission('changeStatus', Arival::class);
    }
}