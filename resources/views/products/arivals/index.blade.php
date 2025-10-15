@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Приходы продукта ' . $product->name,
        'route' => 'products.arival',
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
                            <th>Номер</th>
                            <th>Дата поставки</th>
                            <th>Количество</th>
                            <th>Пользователь</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($arivals as $arival)
                            <tr>
                                <td>{{ $arival['arival']->invoice }}</td>
                                <td>{{ \Carbon\Carbon::parse($arival['arival']->arival_date)->format('d.m.Y') }}</td>
                                <td>{{ $arival['quantity'] }}</td>
                                <td>{{ $arival['arival']->user->surname }} {{ $arival['arival']->user->first_name }}
                                    {{ $arival['arival']->user->middle_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $arival['arival']->status->color() }}">
                                        {{ $arival['arival']->status->name() }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($arival['arival']->created_at)->format('d.m.Y H:i:s') }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
