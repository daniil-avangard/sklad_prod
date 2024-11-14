<?php

namespace App\Console\Commands\Users;

use App\Models\Role;
use Illuminate\Console\Command;
use App\Models\User;

class CreateUserCommand extends Command
{
    protected $signature = 'users:create';

    protected $description = 'Command description';

    public function handle()
    {
        // Запрашиваем данные пользователя с валидацией
        $surname = $this->ask('Ваша фамилия');
        $firstName = $this->ask('Ваше имя');
        $email = $this->ask('Ваш email');
        $password = $this->ask('Ваш пароль'); // Используем secret для пароля

        // Проверяем, существует ли пользователь с таким email
        $user = User::firstOrCreate(
            ['email' => $email],
            ['surname' => $surname, 'first_name' => $firstName, 'password' => $password, 'is_admin' => true]
        );

        $this->warn("ID пользователя: {$user->id}");
        $this->info('Пользователь создан.');

        // Получаем роли в виде массива для выбора
        $roleOptions = Role::all()->pluck('name', 'id')->toArray();

        // Отображаем выбор пользователю с индексами
        $selectedRole = $this->choice('Выберите роль для пользователя', $roleOptions);

        // Находим ID выбранной роли по индексу
        $selectedRoleId = array_search($selectedRole, $roleOptions);

        // Присваиваем выбранную роль пользователю
        $user->roles()->attach($selectedRoleId);

        $this->info('Роль успешно назначена пользователю.');

        return Command::SUCCESS;
    }
}