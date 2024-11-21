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

    <div class="" id="products-info">
        @include('products.info.nav')

        <div class="row">
            <div class="" id="products-info-variants">
                @can('view', App\Models\ProductVariant::class)
                    @include('products.info.variants')
                @endcan
            </div>
            <div class="" id="products-info-division">
                @can('create', \App\Models\Product::class)
                    @include('products.inc.list_division', ['allDivisions' => $allDivisions])
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/assets/js/toggleProductInfo.js"></script>
@endpush
