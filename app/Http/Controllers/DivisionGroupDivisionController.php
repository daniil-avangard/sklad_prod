<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Division;
use App\Models\DivisionGroup;
use App\Models\DivisionGroupDivision;

class DivisionGroupDivisionController extends Controller
{

    public function create(DivisionGroup $group): View
    {
        $divisions = Division::query()
            ->whereNotIn('id', $group->divisions()->pluck('id'))
            ->oldest('name')
            ->get();

        return view('groups.divisions.division.create', compact('group', 'divisions'));
    }

    public function attach(Request $request, DivisionGroup $group)
    {
        $group->divisions()->syncWithoutDetaching($request->division_id);
        return redirect()->route('groups.divisions.show', $group)->with('success', 'Подразделение успешно добавлено в группу');
    }

    public function detach(Request $request, DivisionGroup $group)
    {
        $group->divisions()->detach($request->division_id);
        return redirect()->route('groups.divisions.show', $group)->with('success', 'Подразделение успешно удалено из группы');
    }
}
