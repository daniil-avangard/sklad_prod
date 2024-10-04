<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DivisionGroup;

class UserGroupDivisionController extends Controller
{
    public function create(User $user)
    {
        $groups = DivisionGroup::query()
            ->whereNotIn('id', $user->divisionGroups()->pluck('id'))
            ->oldest('name')
            ->get();
        return view('users.groups.division.create', compact('user', 'groups'));
    }

    public function attach(Request $request, User $user)
    {
        $user->divisionGroups()->syncWithoutDetaching($request->group_id);
        return redirect()->route('users.show', $user);
    }

    public function detach(Request $request, User $user)
    {
        $user->divisionGroups()->detach($request->group_id);
        return redirect()->route('users.show', $user);
    }
}
