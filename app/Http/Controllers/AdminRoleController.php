<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.roles.index');
    }

    
}
