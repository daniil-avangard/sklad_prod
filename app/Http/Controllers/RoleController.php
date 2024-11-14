<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role\CreateRoleRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        if (Gate::denies('view', User::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр прав.');
        }

        $roles = Role::query()
            ->latest()
            ->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(CreateRoleRequest $createRoleRequest)
    {
        $role = Role::create($createRoleRequest->validated());

        return redirect()->route('roles')->with('success', 'Роль успешно добавлена');
    }

    public function show(Role $role)
    {
        $permissions = $role->permissions()->get();

        return view('roles.show', compact('role', 'permissions'));
    }

    public function delete(Role $role)
    {
        if (Gate::denies('delete', User::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр прав.');
        }

        DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->users()->detach();
            $role->delete();
        });

        return redirect()->route('roles')->with('success', 'Роль успешно удалена');
    }
}