<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DivisionGroup;
use App\Models\Division;

class DivisionGroupController extends Controller
{
    public function index()
    {
        $groups = DivisionGroup::all();
        return view('groups.divisions.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.divisions.create');
    }

    public function store(Request $request)
    {
        $group = new DivisionGroup();
        $group->name = $request->name;
        $group->save();
        return redirect()->route('groups.divisions')->with('success', 'Группа подразделений успешно создана');
    }

    public function edit(DivisionGroup $group)
    {
        return view('groups.divisions.edit', compact('group'));
    }

    public function update(Request $request, DivisionGroup $group)
    {
        $group->name = $request->name;
        $group->save();
        return redirect()->route('groups.divisions')->with('success', 'Группа подразделений успешно изменена');
    }

    public function show(DivisionGroup $group)
    {

        $divisions = $group->divisions()->get();
        return view('groups.divisions.show', compact('group', 'divisions'));
    }

    public function delete(DivisionGroup $group)
    {
        $group->delete();
        return redirect()->route('groups.divisions')->with('success', 'Группа подразделений успешно удалена');
    }
}
