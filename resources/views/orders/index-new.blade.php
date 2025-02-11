@extends('layouts.base')


@section('title_page', 'Заказы')

@push('styles-plugins')
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказы',
        'route' => 'orders',
        'breadcrumbs' => 'Заказы',
        // 'add_route' => 'orders.create',
    ])


    <div class="row">
        <div class="col-12">

            <div class="table-responsive">
                @can('view', \App\Models\Order::class)
                    <button id="view-selected" class="btn btn-success mb-3">Просмотреть выбранные заказы</button>
                @endcan
                <table class="table table-bordered custom-table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="js-check-all" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>
<!--                            <th scope="col">ID</th>-->
                            <th scope="col">Подразделение</th>
                            <th scope="col">Товары</th>
                            <th scope="col">Количество</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <th scope="row">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <td>
                                    <a
                                        @can('view', $order)
                                    href="{{ route('orders.show', $order) }}"
                                    @else
                                    href="#"
                                    @endcan>
                                        {{ $order->division->name }}
                                    </a>
                                </td>
<!--                                <td>  Было
                                     $order->division->name 
                                </td>-->
                                <td>
                                    @foreach ($allItems[$order->id] as $item)
                                        <div class="order-popup-parent">
                                            <p>{{ $item['name'] }}</p>
                                            <div class="order-popup-child">
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="" class=" mx-auto  d-block" height="150">
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($allItems[$order->id] as $item)
                                        <p><span>{{ $item['quantity'] }}</span></p>
                                    @endforeach
                                </td>
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
    
    <div class="row">
        <div class="col-12">
            <div class="view">
                <div class="wrapper long-table">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="sticky-col first-col head-bold">Товары</th>
                        @foreach ($divisionNames as $divisionName)
                            <th class="v-table-text head-bold">{{ $divisionName }}</th>
                        @endforeach
                        <th class="head-bold">Заказано</th>
                        <th class="head-bold">На складе</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniqGoods as $good)
                            <tr>
                                <td class="sticky-col first-col">
                                    <div class="order-popup-parent">
                                        <p>{{ $good['name'] }}</p>
                                        <div class="order-popup-child">
                                            <img src="{{ asset('storage/' . $good['image']) }}" alt="" class=" mx-auto  d-block" height="150">
                                        </div>
                                    </div>
                                </td>
                                @foreach ($divisionNames as $divisionName)
                                    <td class="another-col">
                                        @if ($allDivisionsDataNew[$divisionName][$good['name']]['id'] == 0)
                                            <div class="digits-order">
                                                <p>{{ $allDivisionsDataNew[$divisionName][$good['name']]['quontity'] }}</p>
                                                <p>{{ $allDivisionsData[$divisionName][$good['name']]['quontity'] }}</p>
                                            </div>
                                        @else
                                            <div class="digits-order">
                                                <a href="{{ route('orders.show', $allDivisionsDataNew[$divisionName][$good['name']]['orderId']) }}" 
                                                   class="clickForOrder color-for-approve"
                                                   data-type="number" 
                                                   data-pk="{{ $allDivisionsDataNew[$divisionName][$good['name']]['id'] }}" 
                                                   data-title="Введите количество"
                                                   data-origin="{{ $allDivisionsData[$divisionName][$good['name']]['quontity'] }}"
                                                   data-new="{{ $allDivisionsDataNew[$divisionName][$good['name']]['quontity'] }}"
                                                >
                                                    {{ $allDivisionsDataNew[$divisionName][$good['name']]['quontity'] }}
                                                </a>
                                                <p>{{ $allDivisionsData[$divisionName][$good['name']]['quontity'] }}</p>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="another-col">{{ $good['total'] }}</td>
                                <td class="another-col">{{ $good['warehouse'] }}</td>
                            </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>  
        </div>
    </div>
@endsection

@push('scripts-plugins')
<!--    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>-->
<!--    <script src="/assets/pages/orders/update_new.quantity.js"></script>-->
    <script src="/assets/js/checkBoxesOrdersList.js"></script>
    <script src="/assets/js/ordersListElements.js"></script>
@endpush
