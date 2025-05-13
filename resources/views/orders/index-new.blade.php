@extends('layouts.base')


@section('title_page', 'Заказы')

@push('styles-plugins') 
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
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
            <h4 class="page-title-svod-h4">Таблица для утверждения заказов на <span id="month-orders">май</span> месяц</h4>
            <h6 class="page-title-svod-h6">Заказы необходимо проверить и утвердить в период с <span id="date-orders">25</span> числа и до окончания месяца</h6>
                <div class="table-container">
			<table id="excel-table" class="long-table">
                            <tr>
                                <th class="first-col head-bold">Товары</th>
                                @foreach ($divisionNames as $divisionName)
                                    <th class="rotated-table-text head-bold color-division-{{ $divisionName['sort'] }}">{{ $divisionName['name'] }}</th>
                                @endforeach
                                @if ($flagForExcell == "show")
                                <th class="head-bold">Заказано</th>
                                <th class="head-bold">Минимально<br>допустимый<br>остаток</th>
                                <th class="head-bold">Тираж<br>для<br>дозаказа</th>
                                <th class="head-bold">Доступно<br>для<br>заказа</th>
                                <th class="head-bold">Остаток<br>после<br>заказов</th>
                                @endif
                            </tr>
                            @foreach ($uniqGoods as $good)
                                @if (($good['warehouse']-$uniqGoodsTotalOrdered[$good['name']]) / $good['min_stock'] >= 1)
                                <tr>
                                @else
                                    @if ($flagForExcell == "show")
                                    <tr class="row-color">
                                    @else
                                    <tr>
                                    @endif
                                @endif
                                    <td class="first-col-1">
                                        <div class="order-popup-parent">
                                            <p>{{ $good['name'] }}</p>
                                            <div class="order-popup-child order-popup-child-1">
                                                <img src="{{ asset('storage/' . $good['image']) }}" alt="" class="mx-auto d-block visual-events" height="150">
                                            </div>
                                        </div>
                                    </td>
                                    @include('orders.digits-cell')
                                    @if ($flagForExcell == "show")
                                        <td class="another-col">{{ $good['total'] }}</td>
                                        <td class="another-col">{{ $good['min_stock'] }}</td>
                                        <td class="another-col"> - </td>
                                        <td class="another-col">{{ $good['warehouse']-$uniqGoodsTotalOrdered[$good['name']]+$good['total'] }}</td>
                                        <td class="another-col">{{ $good['warehouse']-$uniqGoodsTotalOrdered[$good['name']] }}</td>
                                    @endif
                                </tr>
                            @endforeach
			</table>
		</div>
            @can('view', \App\Models\Order::class)
                <button id="acept-all-orders" class="btn btn-success mb-3">Утвердить все заказы</button>
            @endcan
        </div>
    </div>
@endsection

@push('scripts-plugins')
<!--    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>-->
<!--    <script src="/assets/pages/orders/update_new.quantity.js"></script>-->
<!--    <script src="/assets/js/checkBoxesOrdersList.js"></script>-->
    <script src="/assets/js/ordersListElements.js"></script>
@endpush
