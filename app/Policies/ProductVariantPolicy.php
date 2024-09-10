<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @PolicyName(view="Просмотр варианта продукта", create="Создание варианта продукта", update="Редактирование варианта продукта", delete="Удаление варианта продукта")
 */

class ProductVariantPolicy
{


    public function view(User $user, ProductVariant $productVariant = null): bool
    {
        return $user->hasPermission('view', ProductVariant::class);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create', ProductVariant::class);
    }

    public function update(User $user, ProductVariant $productVariant = null): bool
    {
        return $user->hasPermission('update', ProductVariant::class);
    }

    public function delete(User $user, ProductVariant $productVariant = null): bool
    {
        return $user->hasPermission('delete', ProductVariant::class);
    }

}
