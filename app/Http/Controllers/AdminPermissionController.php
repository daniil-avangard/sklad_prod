<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;



class AdminPermissionController extends Controller
{
    public function create(User $user)
    {
        $permissions = Permission::query()
        ->whereNotIn('id', $user->permissions()->pluck('id'))
        ->oldest()
        ->get();
        return view('admin.permissions.create', compact('user', 'permissions'));
    }

    public function attach(Request $request, User $user)
    {
        $id = $request->input('permission_id');
        $user->permissions()->syncWithoutDetaching($id);

        return redirect()->route('admin.users.permissions.create', $user)->with('success', 'Полномочие успешно добавлено');
    }

    public function detach(Request $request, User $user)
    {
        $id = $request->input('permission_id');
        $user->permissions()->detach($id);

        return redirect()->route('admin.users.permissions.create', $user)->with('success', 'Полномочие успешно удалено');
    }


}
