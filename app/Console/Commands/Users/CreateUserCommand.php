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
        mb_internal_encoding("UTF-8");

        // Запрашиваем данные пользователя с валидацией
        $surname = $this->cleanInput($this->ask('Ваша фамилия'));
        $firstName = $this->cleanInput($this->ask('Ваше имя'));
        $email = $this->cleanEmail($this->ask('Ваш email'));
        $password = $this->cleanPassword($this->ask('Ваш пароль')); // Используем secret для пароля

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

    private function cleanInput(string $input): string
    {
        // Удаляем пробелы в начале и конце
        $cleaned = trim($input);

        // Удаляем спецсимволы, кроме букв, цифр, пробелов и дефисов
        $cleaned = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $cleaned);

        // Проверяем кодировку
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        return $cleaned;
    }

    private function cleanEmail(string $email): string
    {
        $cleaned = trim($email);

        // Разрешаем буквы, цифры, @, точки, дефисы и подчёркивания
        $cleaned = preg_replace('/[^\p{L}\p{N}@._-]/u', '', $cleaned);

        // Проверяем кодировку
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        return $cleaned;
    }

    private function cleanPassword(string $password): string
    {
        $cleaned = trim($password);

        // Проверяем и приводим к кодировке UTF-8, если нужно
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            $cleaned = mb_convert_encoding($cleaned, 'UTF-8', 'auto');
        }

        return $cleaned;
    }
}