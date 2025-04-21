<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;


/**
 * @PolicyName(viewOrders="Просмотр всех заказов в старом варианте", viewAny="Просмотр всех заказов", view="Просмотр заказа", create="Создание заказа", update="Редактирование заказа", delete="Удаление заказа", updateQuantity="Изменение количества заказа", processingStatus="Изменение статуса Проверено куратором", managerProcessingStatus="Изменение статуса Проверено начальником кураторов" transferToWarehouse="Изменение статуса Передано на склад", canceledStatus="Изменение статуса Отмена заказа", viewQuantity="Просмотр количества в заказе")
 */
class OrderPolicy
{
    public function viewOrders(User $user): bool
    {
        return $user->hasPermission('viewOrders', Order::class);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('viewAny', Order::class);
    }

    public function view(User $user, Order $order = null): bool
    {
        return $user->hasPermission('view', Order::class);
    }


    public function create(User $user): bool
    {
        return $user->hasPermission('create', Order::class);
    }


    public function update(User $user, Order $order = null): bool
    {
        return $user->hasPermission('update', Order::class);
    }


    public function delete(User $user, Order $order = null): bool
    {
        return $user->hasPermission('delete', Order::class);
    }

    public function updateQuantity(User $user, Order $order = null): bool
    {
        return $user->hasPermission('updateQuantity', Order::class);
    }

    public function processingStatus(User $user, Order $order = null): bool
    {
        return $user->hasPermission('processingStatus', Order::class);
    }

    public function managerProcessingStatus(User $user, Order $order = null): bool
    {
        return $user->hasPermission('managerProcessingStatus', Order::class);
    }

    public function transferToWarehouse(User $user, Order $order = null): bool
    {
        return $user->hasPermission('transferToWarehouse', Order::class);
    }

    public function canceledStatus(User $user, Order $order = null): bool
    {
        return $user->hasPermission('canceledStatus', Order::class);
    }

    public function viewQuantity(User $user, Order $order = null): bool
    {
        return $user->hasPermission('viewQuantity', Order::class);
    }
}
