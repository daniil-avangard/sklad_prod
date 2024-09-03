<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Главная
Breadcrumbs::for('main', function ($trail) {
    $trail->push('Главная', route('home'));
});

// Пользователи
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('main');
    $trail->push('Пользователи', route('users.index'));
});

// Просмотр пользователя
Breadcrumbs::for('users.show', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push($user ?? 'Без имени', $user);
});

// Полномочия

Breadcrumbs::for('permissions', function ($trail) {
    $trail->parent('main');
    $trail->push('Полномочия', route('permissions'));
});

// Просмотр полномочия

Breadcrumbs::for('permissions.edit', function ($trail, $permission) {
    $trail->parent('permissions');
    $trail->push("Редактирование полномочия", route('permissions.edit', $permission));
});

// Роли

Breadcrumbs::for('roles', function ($trail) {
    $trail->parent('main');
    $trail->push('Роли', route('roles'));
});

// Просмотр роли

Breadcrumbs::for('roles.show', function ($trail, $role) {
    $trail->parent('roles');
    $trail->push('Редактирование роли', route('roles.show', $role));
});

// Приходы

Breadcrumbs::for('arivals', function ($trail) {
    $trail->parent('main');
    $trail->push('Приходы', route('arivals'));
});

// Просмотр прихода

Breadcrumbs::for('arivals.show', function ($trail, $arival) {
    $trail->parent('arivals');
    $trail->push('Просмотр прихода', route('arivals.show', $arival));
});

Breadcrumbs::for('arivals.create', function ($trail) {
    $trail->parent('arivals');
    $trail->push('Добавление прихода', route('arivals.create'));
});

// Продукты

Breadcrumbs::for('products', function ($trail) {
    $trail->parent('main');
    $trail->push('Продукты', route('products'));
});

// Просмотр продукта

Breadcrumbs::for('products.show', function ($trail, $product) {
    $trail->parent('products');
    $trail->push('Просмотр продукта', route('products.show', $product));
});

Breadcrumbs::for('products.create', function ($trail) {
    $trail->parent('products');
    $trail->push('Добавление продукта', route('products.create'));
});

// Редактирование продукта
Breadcrumbs::for('products.edit', function ($trail, $product) {
    $trail->parent('products');
    $trail->push('Редактирование продукта', route('products.edit', $product));
});