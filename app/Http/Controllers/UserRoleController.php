<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\User\Roles\AttachRoleRequest;
use App\Http\Requests\User\Roles\DetachRoleRequest;

class UserRoleController extends Controller
{

    public function getRolesForModal(User $user)
    {
        $rolesAdd = Role::query()
            ->whereNotIn('id', $user->roles()->pluck('id'))
            ->oldest('name')
            ->get();

        return response()->json([
            'rolesAdd' => $rolesAdd
        ]);
    }

    public function create(User $user, Request $request): View
    {
        $roles = Role::query()
            ->whereNotIn('id', $user->roles()->pluck('id'))
            ->oldest('name')
            ->get();

        return view('users.roles.create', compact('user', 'roles'));
    }

    public function attach(AttachRoleRequest $request, User $user): RedirectResponse
    {
        $id = $request->input('role_id');

        $user->roles()->syncWithoutDetaching($id);

        return to_route('users.show', $user);
    }

    public function detach(DetachRoleRequest $request, User $user): RedirectResponse
    {
        $id = $request->input('role_id');

        $user->roles()->detach($id);

        return to_route('users.show', $user);
    }
}
