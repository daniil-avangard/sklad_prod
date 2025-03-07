<?php

namespace App\Policies;

use App\Models\Korobka;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


/**
 * @PolicyName(viewAny="Просмотр всех заказов на сборку", view="Просмотр заказа на сборку", create="Создание трек номера для коробки", update="Обновление трек номера коробки", delete="Удаление трек номера коробки", changeStatus="Изменение статуса заказа на сборке")
 */
class KorobkaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('viewAny', Korobka::class);
    }

    public function view(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('view', Korobka::class);
    }

    public function create(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('create', Korobka::class);
    }

    public function update(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('update', Korobka::class);
    }

    public function delete(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('delete', Korobka::class);
    }

    public function changeStatus(User $user, Korobka $korobka = null): bool
    {
        return $user->hasPermission('changeStatus', Korobka::class);
    }
}