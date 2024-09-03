<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Writeoff;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

/**
 * @PolicyName(view="Просмотр списания", create="Создание списания", update="Редактирование списания", delete="Удаление списания", changeStatus="Изменение статуса списания")
 */
class WriteoffPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Writeoff $writeoff = null): bool
    {
        return $user->hasPermission('view', Writeoff::class);
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('create', Writeoff::class);
    }


    public function update(User $user, Writeoff $writeoff = null): bool
    {
        return $user->hasPermission('update', Writeoff::class);
    }


    public function delete(User $user, Writeoff $writeoff = null): bool
    {
        return $user->hasPermission('delete', Writeoff::class);
    }

 
    public function changeStatus(User $user, Writeoff $writeoff = null): bool
    {
        return $user->hasPermission('changeStatus', Writeoff::class);
    }
}
