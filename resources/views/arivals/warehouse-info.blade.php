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
            <h4 class="page-title-svod-h4">Таблица, необходимых для отправки заказов на <span id="month-orders">май</span></h4>
            <h6 class="page-title-svod-h6">Утвержденные заказы в период с <span id="date-orders">25</span> числа и до окончания месяца</h6>
                <div class="table-container">
			<table id="excel-table" class="long-table">
                            <tr>
                                <th class="first-col head-bold bckgrnd-table-cell-2">Товары</th>
                                @foreach ($divisionNames as $divisionName)
                                    <th class="rotated-table-text head-bold color-division-{{ $divisionName['sort'] }} bckgrnd-table-cell-2">{{ $divisionName['name'] }}</th>
                                @endforeach
                                @if ($flagForExcell == "show")
                                <th class="head-bold bckgrnd-table-cell-2 for-anothe-col-width for-another-column-100">Заказано</th>  
                                <th class="head-bold bckgrnd-table-cell-2 for-anothe-col-width for-another-column-10">Доступно<br>для<br>заказа</th>
                                <th class="head-bold bckgrnd-table-cell-2 for-anothe-col-width for-last-column">Остаток<br>после<br>заказов</th>                             
                                @endif
                            </tr>
                            @foreach ($uniqGoods as $good)
                                @if (($good['warehouse']-$uniqGoodsTotalOrdered[$good['name']]) - $good['min_stock'] >= 0)
                                <tr>
                                @else
                                    @if ($flagForExcell == "show" && ($good['warehouse']-$uniqGoodsTotalOrdered[$good['name']] + $uniqGoodsNewOrdered[$good['name']]) < 0)
                                    <tr class="row-color">
                                    @else
                                    @if ($flagForExcell == "show" && ($good['warehouse']-$uniqGoodsTotalOrdered[$good['name']] + $uniqGoodsNewOrdered[$good['name']]) >= 0)
                                    <tr class="row-color-accept">
                                    @else
                                    <tr> 
                                    @endif
                                    @endif
                                @endif
                                    <td class="first-col-1">
                                        <div class="flex-excel">
                                            <div class="order-popup-parent flex-excel-elm-name">
                                                <p>{{ $good['name'] }}</p>
                                                <div class="order-popup-child order-popup-child-1">
                                                    <img src="{{ asset('storage/' . $good['image']) }}" alt="" class="mx-auto d-block visual-events popup-child-img" height="150">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @include('arivals.digits-cell')
                                    @if ($flagForExcell == "show")
                                        <td class="another-col tr-another-cell for-anothe-col-width for-another-column-100">{{ $good['total'] - $totalNewData[$good['name']] }}</td>
                                        <td class="another-col tr-another-cell for-anothe-col-width for-another-column-10">{{ $good['warehouse']-$uniqGoodsTotalOrdered[$good['name']]+$good['total'] - $totalNewData[$good['name']] + $uniqGoodsNewOrdered[$good['name']] }}</td>
                                        <td class="another-col tr-another-cell for-anothe-col-width for-last-column">{{ $good['warehouse']-$uniqGoodsTotalOrdered[$good['name']] + $uniqGoodsNewOrdered[$good['name']] }}</td>                         
                                    @endif
                                </tr>
                            @endforeach
			</table>
		</div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
<!--    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>-->
<!--    <script src="/assets/pages/orders/update_new.quantity.js"></script>-->
<!--    <script src="/assets/js/checkBoxesOrdersList.js"></script>-->
    <script src="/assets/js/warehouseData.js"></script>
@endpush
