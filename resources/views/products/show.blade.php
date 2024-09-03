@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Продукт ' . $product->name,
    'route' => 'products',
    'breadcrumbs' => 'Продукты',
    'back_route' => 'products',
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Продукт {{ $product->name }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>
</div>

@endsection