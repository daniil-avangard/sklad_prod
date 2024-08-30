<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;




class AdminPermissionController extends Controller
{
    public function getPermissionsForModal(User $user)
    {
        $permissionsAdd = Permission::query()
            ->whereNotIn('id', $user->permissions()->pluck('id'))
            ->oldest('action')
            ->get();

        return response()->json([
            'permissionsAdd' => $permissionsAdd
        ]);
    }

    public function attach(Request $request, User $user)
    {
        $id = $request->input('permission_id');
        $user->permissions()->syncWithoutDetaching($id);

        return redirect()->route('users.show', $user)->with('success', 'Полномочие успешно добавлено');
    }

    public function detach(Request $request, User $user)
    {
        $id = $request->input('permission_id');
        $user->permissions()->detach($id);

        return redirect()->route('users.show', $user)->with('success', 'Полномочие успешно удалено');
    }


}
