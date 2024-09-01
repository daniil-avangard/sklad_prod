<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role\CreateRoleRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
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
        DB::transaction(function () use ($role) {
            $role->permissions()->detach();
            $role->users()->detach();
            $role->delete();
        });

        return redirect()->route('roles')->with('success', 'Роль успешно удалена');
    }
}
