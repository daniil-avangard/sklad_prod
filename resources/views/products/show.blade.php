@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Продукт ' . $product->name,
    'route' => 'products',
    'breadcrumbs' => 'Продукты',
    'back_route' => 'products',
])




<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0">
        <li class="nav-item">
        <a class="nav-link active">Просмотр</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{ route('products.edit', $product->id) }}">Редактировать</a>
        </li>
    </ul>        
</div>
@include('products.info.header')





<div class="row">
    <div class="col-lg-9">

    @include('products.info.variants')

    </div>
    <div class="col-lg-3">
    @include('products.inc.arivals', ['arivals' => $arivals])
    @include('products.inc.writeoffs', ['writeOffs' => $writeOffs])
    </div>
    
</div>
<div class="row">
    <div class="col-12">
        @include('products.inc.list_division', ['divisions' => $divisions])
        
    </div>
</div>

@endsection