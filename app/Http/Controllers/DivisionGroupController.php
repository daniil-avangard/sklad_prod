<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DivisionGroup;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class DivisionGroupController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', Product::class);
        $canCreateProduct = Gate::allows('create', Product::class);

        $groups = DivisionGroup::all();
        return view('groups.divisions.index', compact('groups', 'canCreateProduct'));
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
        $this->authorize('update', Product::class);

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
