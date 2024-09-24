@extends('layouts.base')

@section('content')
    {{-- @include('includes.breadcrumb', [
        'title' => 'Просмотр заказа',
        'route' => 'user.order',
        'breadcrumbs' => 'Просмотр заказа',
        'back_route' => 'user.orders',
    ]) --}}
    @include('users.inc.head')

    @include('users.inc.nav')

    <table class="table mb-0 table-responsive">
        <thead>
            <tr>
                <th class="border-top-0">Наименование</th>
                <th class="border-top-0">даты выпуска, разрешенные к рапространению</th>
                <th class="border-top-0">Категория</th>
                <th class="border-top-0">Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $orderItem)
                <tr>
                    <td>
                        <img src="{{ asset('/storage/' . $orderItem->product->image) }}" alt="" height="36">
                        <p class="d-inline-block align-middle mb-0">
                            <a href="{{ route('products.info', $orderItem->product) }}"
                                class="d-inline-block align-middle mb-0 product-name">{{ $orderItem->product->name }}</a>
                            <br>
                            <span class="text-muted font-13">{{ $orderItem->product->sku }}</span>
                        </p>
                    </td>
                    <td>
                        @php
                            $dateOfActualities = $orderItem->product->variants
                                ->where('is_active', true)
                                ->pluck('date_of_actuality')
                                ->unique()
                                ->values()
                                ->sortDesc();
                        @endphp
                        @foreach ($dateOfActualities as $dateOfActuality)
                            @if (is_null($dateOfActuality))
                                <p class="m-0">Без даты</p>
                            @endif
                            <p class="m-0">
                                {{ \Carbon\Carbon::parse($dateOfActuality)->format('d.m.Y') }}</p>
                        @endforeach
                    </td>
                    <td>{{ $orderItem->product->category->name }}</td>
                    <td>{{ $orderItem->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
