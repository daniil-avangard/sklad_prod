<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UdateUserRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;



class UserController extends Controller
{
    public function index()
    {

        if (Gate::denies('view', User::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр пользователей.');
        }

        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (Gate::denies('create', User::class)) {
            throw new AuthorizationException('У вас нет разрешения на создание пользователя.');
        }

        return view('users.create');
    }

    public function store(UserRequest $userRequest)
    {

        if (Gate::denies('create', User::class)) {
            throw new AuthorizationException('У вас нет разрешения на создание пользователя.');
        }

        $data = $userRequest->only(['surname', 'first_name', 'midle_name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        
        return redirect()->route('users.index')->with('success', 'Пользователь успешно создан');
    }

    public function edit(User $user)
    {
        if (Gate::denies('update', $user)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование пользователя.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(UdateUserRequest $updateUserRequest, User $user)
    {
        if (Gate::denies('update', $user)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование пользователя.');
        }

        $data = $updateUserRequest->only(['surname', 'first_name', 'middle_name', 'email']);
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Пользователь успешно обновлен');
    }

    public function show(User $user)
    {
        if (Gate::denies('view', $user)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр пользователя.');
        }

        $permissions = $user->permissions()->get();
        $roles = $user->roles()->get();
        return view('users.show', compact('user', 'permissions', 'roles'));
    }

    public function delete(User $user)
    {
        if (Gate::denies('delete', $user)) {
            throw new AuthorizationException('У вас нет разрешения на удаление пользователя.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Пользователь успешно удален');
    }
    
}
