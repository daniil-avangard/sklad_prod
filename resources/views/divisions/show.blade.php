@extends('layouts.base')

@section('content')
@include('includes.breadcrumb', [
    'title' => 'Просмотр подразделения',
    'route' => 'divisions.show',
    'breadcrumbs' => 'Подразделения',
    'back_route' => 'divisions',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Название: {{ $division->name }}</h5>
                </div>
            </div>
        </div>
    </div>

    @can('create', \App\Models\Product::class)
    <div class="row">
        <div class="col-12">
            @include('divisions.inc.list_product', ['products' => $products])
        </div>
    </div>
    @endcan
@endsection
