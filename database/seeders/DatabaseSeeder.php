<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'surname' => 'Admin',
                'first_name' => 'Test',
                'middle_name' => null,
                'password' => 'password123',
                'is_admin' => true,
                'remember_token' => Str::random(10),
            ]
        );

        $superRole = Role::updateOrCreate(
            ['value' => UserRoleEnum::SUPER_ADMIN->value],
            [
                'name' => UserRoleEnum::SUPER_ADMIN->label(),
                'super' => true,
            ]
        );

        $user->roles()->syncWithoutDetaching([$superRole->id]);
    }
}
