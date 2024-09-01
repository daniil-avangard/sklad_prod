<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arival;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Product;


class ArivalController extends Controller
{
    public function index()
    {
        return view('arivals.index');
    }

    public function create()
    {
        $products = Product::all();

        return view('arivals.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
    }

    public function edit($id)
    {
        return view('arivals.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
    }

    public function destroy($id)
    {
        return view('arivals.destroy', compact('id'));
    }

    public function show($id)
    {
        return view('arivals.show', compact('id'));
    }
}
