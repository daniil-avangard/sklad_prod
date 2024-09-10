<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @PolicyName(view="Просмотр продукта", create="Создание продукта", update="Редактирование продукта", delete="Удаление продукта")
 */

class ProductPolicy
{


    public function view(User $user, Product $product = null): bool
    {
        return $user->hasPermission('view', Product::class);
    }

   
    public function create(User $user): bool
    {
        return $user->hasPermission('create', Product::class);
    }

 
    public function update(User $user, Product $product = null): bool
    {
        return $user->hasPermission('update', Product::class);
    }


    public function delete(User $user, Product $product = null): bool
    {
        return $user->hasPermission('delete', Product::class);
    }





}
