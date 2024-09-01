<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Role\Permission\AttachPermissionRequest;
use App\Http\Requests\Role\Permission\DetachPermissionRequest;



class RolePermissionController extends Controller
{

    public function getPermissionsForModal(Role $role)
    {
        $permissionsAdd = Permission::query()
            ->whereNotIn('id', $role->permissions()->pluck('id'))
            ->oldest('action')
            ->get();

        return response()->json([
            'permissionsAdd' => $permissionsAdd
        ]);
    }

    public function create(Role $role): View
    {
        $permissions = Permission::query()
            ->whereNotIn('id', $role->permissions()->pluck('id'))
            ->oldest('action')
            ->get();

        return view('roles', compact('role', 'permissions'));
    }

    public function attach(AttachPermissionRequest $request, Role $role): RedirectResponse
    {
        $id = $request->input('permission_id');

        $role->permissions()->syncWithoutDetaching($id);

        return to_route('roles.show', $role);
    }

    public function detach(DetachPermissionRequest $request, Role $role): RedirectResponse
    {
        $id = $request->input('permission_id');

        $role->permissions()->detach($id);

        return to_route('roles.show', $role);
    }
}
