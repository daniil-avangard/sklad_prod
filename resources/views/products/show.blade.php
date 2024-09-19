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



    <div class="row">
        <div class="col-lg-9">
            @can('view', App\Models\ProductVariant::class)
                @include('products.info.variants')
            @endcan

        </div>
        <div class="col-lg-3">

            @include('products.inc.list_division', ['divisions' => $divisions])
        </div>
    </div>
    <div class="row">
        <div class="col-3">

        </div>
    </div>
@endsection
