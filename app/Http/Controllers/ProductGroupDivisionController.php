<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DivisionGroup;


class ProductGroupDivisionController extends Controller
{

    public function create(Product $product)
    {
        $groups = DivisionGroup::query()
            ->whereNotIn('id', $product->divisions()->pluck('id'))
            ->oldest('name')
            ->get();

        return view('products.groups.divisions.create', compact('product', 'groups'));
    }

    public function attach(Request $request, Product $product)
    {
        $product->divisionGroups()->syncWithoutDetaching($request->group_id);
        return redirect()->route('products.show', $product);
    }

    public function detach(Request $request, Product $product)
    {
        $product->divisionGroups()->detach($request->division_id);
        return redirect()->route('products.show', $product);
    }
}
