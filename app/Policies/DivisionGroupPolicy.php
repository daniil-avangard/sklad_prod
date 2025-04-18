<?php

namespace App\Policies;

use App\Models\DivisionGroup;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @PolicyName(viewAny="Просмотр списка группы подразделений", view="Просмотр группы подразделений", create="Создание группы подразделений", update="Редактирование группы подразделений", delete="Удаление группы подразделений")
 */
class DivisionGroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('viewAny', DivisionGroup::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermission('view', DivisionGroup::class);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create', DivisionGroup::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update', DivisionGroup::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermission('delete', DivisionGroup::class);
    }
}
