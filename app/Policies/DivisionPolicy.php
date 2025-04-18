<?php

namespace App\Policies;

use App\Models\Division;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @PolicyName(viewAny="Просмотр списка подразделений", view="Просмотр подразделения", create="Создание подразделения", update="Редактирование подразделения", delete="Удаление подразделения")
 */
class DivisionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('viewAny', Division::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermission('view', Division::class);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create', Division::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update', Division::class);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermission('delete', Division::class);
    }
}
