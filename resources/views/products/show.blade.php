@extends('layouts.base')

@section('title_page', $product->name)

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Продукт ' . $product->name,
        'route' => 'products',
        'breadcrumbs' => 'Продукты',
        'back_route' => 'products',
    ])

    @include('products.info.header')

    @include('products.info.nav')

    <div class="row">
        <div class="">
            @if (!is_null($variants))
                @can('view', App\Models\ProductVariant::class)
                    @include('products.info.variants')
                @endcan
            @endif
        </div>
        <div class="">
            @if (!is_null($divisions))
                @can('create', \App\Models\Product::class)
                    @include('products.inc.list_division', ['divisions' => $divisions])
                @endcan
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-3">

        </div>
    </div>
@endsection
