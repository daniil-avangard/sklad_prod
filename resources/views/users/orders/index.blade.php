@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказы',
        'route' => 'user.orders',
        'breadcrumbs' => 'Заказы',
    ])
    @include('users.inc.head')

    @include('users.inc.nav')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Заказы</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('user.order', ['order' => $order]) }}">{{ $order->id }}</a>
                                    </td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
