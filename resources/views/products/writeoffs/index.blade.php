@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Списания продукта ' . $product->name,
        'route' => 'products.writeoff',
        'breadcrumbs' => $product,
        'back_route' => 'products.show',
        'back_route_param' => $product,
    ])

    @include('products.inc.nav')

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Причина</th>
                            <th>Дата списания</th>
                            <th>Количество</th>
                            <th>Пользователь</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($writeoffs as $writeoff)
                            <tr>
                                <td>{{ $writeoff['writeoff']->reason }}</td>
                                <td>{{ \Carbon\Carbon::parse($writeoff['writeoff']->writeoff_date)->format('d.m.Y') }}</td>
                                <td>{{ $writeoff['quantity'] }}</td>
                                <td>{{ $writeoff['writeoff']->user->surname }} {{ $writeoff['writeoff']->user->first_name }}
                                    {{ $writeoff['writeoff']->user->middle_name }}</td>            
                                <td>
                                    <span class="badge bg-{{ $writeoff['writeoff']->status->color() }}">
                                        {{ $writeoff['writeoff']->status->name() }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($writeoff['writeoff']->created_at)->format('d.m.Y H:i:s') }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
