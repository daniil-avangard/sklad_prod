<?php

namespace App\Console\Commands\Users;

use Illuminate\Console\Command;
use App\Models\User;

class CreateUserCommand extends Command
{

    protected $signature = 'users:create';


    protected $description = 'Command description';

    public function handle()
    {
        $user = User::create([
            'surname' => $this->ask('Ваша фамилия'),
            'first_name' => $this->ask('Ваше имя'),
            'email' => $this->ask('Ваш email'),
            'password' => $this->ask('Ваш пароль'),
        ]);


        $this->warn("ID {$user->id}");
        
        $this->info('Пользователь создан');

        return Command::SUCCESS;

    }
}
