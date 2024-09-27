@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Просмотр нескольких заказов',
        'route' => 'orders.selected',
        'breadcrumbs' => 'Просмотр нескольких заказов',
        'back_route' => 'orders',
    ])

    <div class="row">
        <div class="col-12">

            <div class="table-responsive">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Наименование товара</th>
                            <th scope="col">Доступное кол-во</th>
                            <th scope="col">Заказаное кол-во</th>
                            @foreach ($orders as $order)
                                <th scope="col">
                                    <a href="{{ route('orders.show', $order->id) }}">
                                        {{ $order->division->name }}
                                        <br>
                                        ({{ $order->user->surname }} {{ $order->user->first_name }}
                                        {{ $order->user->middle_name }})
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allItems as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['total_variants'] }}</td>
                                <td class="@if ($item['total_variants'] < $item['quantity']) bg-danger text-white @endif">
                                    {{ $item['quantity'] }}
                                </td>
                                @foreach ($orders as $order)
                                    <td>
                                        @php
                                            $quantity =
                                                $order->items->where('product_id', $item['product_id'] ?? null)->first()
                                                    ->quantity ?? '-';
                                        @endphp
                                        {{ $quantity }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
@endsection
