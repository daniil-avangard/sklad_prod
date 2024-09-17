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
    $trail->push($product->name, route('products.show', $product));
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

// Приходы продукта

Breadcrumbs::for('products.arival', function ($trail, $product) {
    $trail->parent('products.show', $product);
    $trail->push('Приходы', route('products.arival', $product));
});

// Списание продукта

Breadcrumbs::for('products.writeoff', function ($trail, $product) {
    $trail->parent('products.show', $product);
    $trail->push('Списание', route('products.writeoff', $product));
});

// Списание

Breadcrumbs::for('writeoffs', function ($trail) {
    $trail->parent('main');
    $trail->push('Списание', route('writeoffs'));
});

// Просмотр списания

Breadcrumbs::for('writeoffs.show', function ($trail, $writeoff) {
    $trail->parent('writeoffs');
    $trail->push('Просмотр списания', route('writeoffs.show', $writeoff));
});

Breadcrumbs::for('writeoffs.create', function ($trail) {
    $trail->parent('writeoffs');
    $trail->push('Добавление списания', route('writeoffs.create'));
});

// Подразделения

Breadcrumbs::for('divisions', function ($trail) {
    $trail->parent('main');
    $trail->push('Подразделения', route('divisions'));
});

Breadcrumbs::for('divisions.create', function ($trail) {
    $trail->parent('divisions');
    $trail->push('Добавление подразделения', route('divisions.create'));
});

Breadcrumbs::for('divisions.show', function ($trail, $division) {
    $trail->parent('divisions');
    $trail->push('Просмотр подразделения', route('divisions.show', $division));
});

Breadcrumbs::for('divisions.edit', function ($trail, $division) {
    $trail->parent('divisions');
    $trail->push('Редактирование подразделения', route('divisions.edit', $division));
});

// Продукты Варианты

Breadcrumbs::for('products.variants', function ($trail, $product) {
    $trail->parent('products');
    $trail->push('Варианты продукта', route('products.variants', $product));
});

Breadcrumbs::for('products.variants.create', function ($trail, $product) {
    $trail->parent('products');
    $trail->push('Добавление варианта продукта', route('products.variants.create', $product));
});

Breadcrumbs::for('products.variants.edit', function ($trail, $product, $variant) {
    $trail->parent('products.variants', $product);
    $trail->push('Редактирование варианта продукта', route('products.variants.edit', ['product' => $product, 'variant' => $variant]));
});

// Добавление подразделения Продукту
Breadcrumbs::for('products.divisions.create', function ($trail, $product) {
    $trail->parent('products.show', $product);
    $trail->push('Добавление подразделения', route('products.divisions.create', $product));
});

// Компании

Breadcrumbs::for('companies', function ($trail) {
    $trail->parent('main');
    $trail->push('Компании', route('companies'));
});

Breadcrumbs::for('companies.create', function ($trail) {
    $trail->parent('companies');
    $trail->push('Добавление компании', route('companies.create'));
});

Breadcrumbs::for('companies.show', function ($trail, $company) {
    $trail->parent('companies');
    $trail->push('Просмотр компании', route('companies.show', $company));
});

Breadcrumbs::for('companies.edit', function ($trail, $company) {
    $trail->parent('companies');
    $trail->push('Редактирование компании', route('companies.edit', $company));
});


// Категории

Breadcrumbs::for('categories', function ($trail) {
    $trail->parent('main');
    $trail->push('Категории', route('categories'));
});

Breadcrumbs::for('categories.create', function ($trail) {
    $trail->parent('categories');
    $trail->push('Добавление категории', route('categories.create'));
});

Breadcrumbs::for('categories.show', function ($trail, $category) {
    $trail->parent('categories');
    $trail->push('Просмотр категории', route('categories.show', $category));
});

Breadcrumbs::for('categories.edit', function ($trail, $category) {
    $trail->parent('categories');
    $trail->push('Редактирование категории', route('categories.edit', $category));
});


// Настройки пользователя

Breadcrumbs::for('user.settings', function ($trail) {
    $trail->parent('main');
    $trail->push('Настройки пользователя', route('user.settings'));
});
