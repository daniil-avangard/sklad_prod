<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::query()->latest()->get();
        return view('permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validated();
        $data['type'] = 'custom';
        
        $permission = Permission::query()->create($data);

        return to_route('permissions.show', $permission)->with('success', 'Полномочие успешно создано');
    }

    public function edit(Permission $permission): View
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);
        
        $permission->update($data);

        return to_route('permissions')->with('success', 'Полномочие успешно обновлено');
    }


    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Полномочие успешно удалено');
    }
}



    

