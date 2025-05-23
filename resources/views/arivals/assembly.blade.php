@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Сборка',
    'route' => 'assembly',
    'breadcrumbs' => 'Сборка',
])

    <div class="row">
        <div class="col-12">

            <div class="table-responsive">
                @can('viewAny', App\Models\Korobka::class)
                    <button id="view-selected" class="btn btn-success mb-3">Просмотреть выбранные заказы</button>
                @endcan
                <table class="table table-bordered custom-table">
                    <thead>
                        <tr>
<!--                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="js-check-all" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>-->
                            <th scope="col">ID</th>
                            <th scope="col">Подразделение</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listForAssmbling as $order)
                            <tr>
<!--                                <th scope="row">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>-->
                                <td>
                                    <a
                                    @can('view', App\Models\Korobka::class)
                                    href="{{ route('assembly.show', $order) }}"
                                    @else
                                    href="#"
                                    @endcan>
                                        Заказ № {{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->division->name }}</td>
                                <td><span class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
