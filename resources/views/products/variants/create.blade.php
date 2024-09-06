@extends('layouts.base')


@section('content')
@include('includes.breadcrumb', [
    'title' => 'Добавить вариант продукта: ' . $product->name,
    'route' => 'products.variants.create',
    'breadcrumbs' => 'Продукты',
])



@endsection
