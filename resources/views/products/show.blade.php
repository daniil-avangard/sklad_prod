@extends('layouts.base')

@push('styles-plugins')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('title_page', $product->name)

@section('content')
    @can('viewAny', \App\Models\Order::class)
        @include('includes.breadcrumb', [
            'title' => 'Продукт ' . $product->name,
            'route' => 'products.show',
            'breadcrumbs' => $product,
            'back_route' => 'products',
        ])
    @endcan

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
                    @include('products.inc.list_division', [
                        'allDivisions' => $allDivisions,
                        'isAllDivisionsSelected' => $isAllDivisionsSelected,
                    ])
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/assets/js/toggleProductInfo.js"></script>
@endpush
