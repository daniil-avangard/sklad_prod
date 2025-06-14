@extends('layouts.base')


@section('title_page', 'Заказы')

@push('scripts-plugins')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/stock/modules/stock.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endpush

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <style>
        /* Custom Checkbox */
        .control {
            display: block;
            position: relative;
            margin-bottom: 25px;
            cursor: pointer;
            font-size: 18px;
        }

        .control input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .control__indicator {
            position: absolute;
            top: 2px;
            left: 0;
            height: 11px;
            width: 11px;
            border-radius: 2px;
            border: 2px solid #ccc;
            background: transparent;
        }

        .control--radio .control__indicator {
            border-radius: 50%;
        }

        .control:hover input~.control__indicator,
        .control input:focus~.control__indicator {
            border: 2px solid #007bff;
        }

        .control input:checked~.control__indicator {
            border: 2px solid #007bff;
            background: #007bff;
        }

        .control input:disabled~.control__indicator {
            background: #e6e6e6;
            opacity: 0.6;
            pointer-events: none;
            border: 2px solid #ccc;
        }

        .control__indicator:after {
            font-family: 'icomoon';
            content: '\e5ca';
            position: absolute;
            display: none;
        }

        .control input:checked~.control__indicator:after {
            display: block;
            color: #fff;
        }

        .control--checkbox .control__indicator:after {
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -52%);
            -ms-transform: translate(-50%, -52%);
            transform: translate(-50%, -52%);
        }

        .control--checkbox input:disabled~.control__indicator:after {
            border-color: #7b7b7b;
        }

        .control--checkbox input:disabled:checked~.control__indicator {
            background-color: #007bff;
            opacity: .2;
            border: 2px solid #007bff;
        }

        .bg-started-war {
            background-color: #0e77ac !important;
        }

        .bg-assembled {
            background-color: #0a567c !important;
        }

        tr p {
            margin-bottom: 5px !important;
        }

        .order-popup-parent {
            position: relative;
            display: block;
            cursor: pointer;
            user-select: none;
        }

        .order-popup-child {
            position: absolute;
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        .order-popup-child-near-top {
            position: absolute;
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            z-index: 1;
            bottom: -575%;
            left: 50%;
            margin-left: -80px;
            z-index: 100 !important;
        }
        .show {
            visibility: visible;
            animation: fadeIn 0.1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
        .order-filters {
            display: flex;
            flex-direction: column;
            justify-content: left;
            margin-right: 10px;
            margin-bottom: 5px;
        }
        .block-filters-index {
            display: flex;
            flex-direction: row;
        }
        .highChart {
            width: 100%;
            height: 250px;
            border: 0.5px solid #eaf0f9;
        }
    </style>
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

<!--                @can('view', \App\Models\Order::class)
                    <button id="view-selected" class="btn btn-success mb-3">Просмотреть выбранные заказы</button>
                @endcan-->
                <div class="block-filters-index">
                    @can('viewAny', \App\Models\Order::class)
                    <div class="order-filters">
                        <div class="filters-work-part">
                            <label for="divisions">Город:</label>
                            <select name='divisions' id='divisiones-names' class="index-top-filters">
                                <option value="">Все</option>
                                @foreach ($groupDivisionsNames1 as $divisionName)
                                    <option value="{{ $divisionName['name'] }}">{{ $divisionName['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
<!--                        <div class="filters-button-part">
                            <button class="select-work-buttons clean-filters">Очистить фильтры</button>
                        </div>-->
                    </div>
                    @endcan
                    
                    <div class="order-filters">
                        <div class="filters-work-part">
                            <label for="statusOfOrder">Статус:</label>
                            <select name='statusOfOrder' id='status-of-orders' class="index-top-filters">
                                <option value="">Все</option>
                                @foreach ($allOrdersStatus as $statusOrder)
                                    <option value="{{ $statusOrder['value'] }}">{{ $statusOrder['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
<!--                        <div class="filters-button-part">
                            <button id="clean-status-product" class="select-work-buttons">Очистить Статусы и Товары</button>
                        </div>-->
                    </div>
                    
                    <div class="order-filters">
                        <div class="filters-work-part">
                            <label for="productsOfOrders">Товары:</label>
                            <select name='productsOfOrders' id='products-of-orders' class="index-top-filters">
                                <option value="">Все</option>
                                @foreach ($allOrdersProducts as $productOrder)
                                    <option value="{{ $productOrder['name'] }}">{{ $productOrder['name'] }}</option>
                                @endforeach
                            </select>
<!--                            <button id="grafic-button">График по городам</button>-->
                        </div>
                        <div class="filters-button-part">
<!--                            <button id="clean-months" class="select-work-buttons">Очистить месяцы</button>-->
                            <button class="select-work-buttons clean-filters">Очистить фильтры</button>
                        </div>
                    </div>
                    
                    <div class="order-filters">

                        <div>Период:</div>
                        <div class="filters-work-part filters-work-part-months filters-graph-part">             

                            <fieldset id="month-field" class="month-field">
                                <div class="month-field-legend">2025</div>
                                <input id="jan2016" value="012025" type="checkbox" name="month2016" />
                                <label for="jan2016">Янв</label>
                                <input id="feb2016" value="022025" type="checkbox" name="month2016" />
                                <label for="feb2016">Фев</label>
                                <input id="mar2016" value="032025" type="checkbox" name="month2016" />
                                <label for="mar2016">Мар</label>
                                <input id="apr2016" value="042025" type="checkbox" name="month2016" />
                                <label for="apr2016">Апр</label>
                                <input id="may2016" value="052025" type="checkbox" name="month2016" />
                                <label for="may2016">Май</label>
                                <input id="jun2016" value="062025" type="checkbox" name="month2016" />
                                <label for="jun2016">Июн</label>
                                <input id="jul2016" value="072025" type="checkbox" name="month2016" />
                                <label for="jul2016">Июл</label>
                                <input id="aug2016" value="082025" type="checkbox" name="month2016" />
                                <label for="aug2016">Авг</label>
                                <input id="sep2016" value="092025" type="checkbox" name="month2016" />
                                <label for="sep2016">Сен</label>
                                <input id="oct2016" value="102025" type="checkbox" name="month2016" />
                                <label for="oct2016">Окт</label>
                                <input id="nov2016" value="112025" type="checkbox" name="month2016" />
                                <label for="nov2016">Ноя</label>
                                <input id="dec2016" value="122025" type="checkbox" name="month2016" />
                                <label for="dec2016">Дек</label>         
                            </fieldset>
                            <fieldset class="month-field month-field-fielsets">
                                <div class="month-field-legend">2024</div>
                                <input id="jan2024" value="012024" type="checkbox" name="month2016" />
                                <label for="jan2024">Янв</label>
                                <input id="feb2024" value="022024" type="checkbox" name="month2016" />
                                <label for="feb2024">Фев</label>
                                <input id="mar2024" value="032024" type="checkbox" name="month2016" />
                                <label for="mar2024">Мар</label>
                                <input id="apr2024" value="042024" type="checkbox" name="month2016" />
                                <label for="apr2024">Апр</label>
                                <input id="may2024" value="052024" type="checkbox" name="month2016" />
                                <label for="may2024">Май</label>
                                <input id="jun2024" value="062024" type="checkbox" name="month2016" />
                                <label for="jun2024">Июн</label>
                                <input id="jul2024" value="072024" type="checkbox" name="month2016" />
                                <label for="jul2024">Июл</label>
                                <input id="aug2024" value="082024" type="checkbox" name="month2016" />
                                <label for="aug2024">Авг</label>
                                <input id="sep2024" value="092024" type="checkbox" name="month2016" />
                                <label for="sep2024">Сен</label>
                                <input id="oct2024" value="102024" type="checkbox" name="month2016" />
                                <label for="oct2024">Окт</label>
                                <input id="nov2024" value="112024" type="checkbox" name="month2016" />
                                <label for="nov2024">Ноя</label>
                                <input id="dec2024" value="122024" type="checkbox" name="month2016" />
                                <label for="dec2024">Дек</label>         
                            </fieldset>
                        </div>
                        <div class="filters-button-part">  
                            <button id="grafic-months" class="select-work-buttons">Построить график</button>
                        </div>
                    </div>
                    
                </div>

                <table id="orders-table" class="table table-bordered custom-table">
                    <thead>
                        <tr>
<!--                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="js-check-all" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>
                            </th>-->
<!--                        <th scope="col">ID</th>-->
                            <th scope="col">Подразделение</th>
                            <th scope="col">Товары</th>
                            <th scope="col">Количество</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="order-rows">
<!--                                <th scope="row">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>-->
                                <td>
                                    @can('view', $order)
                                    <a     
                                        href="{{ route('orders.show', $order) }}">
                                    {{ $order->division->name }}
                                    </a>
                                    @else
                                    {{ $order->division->name }}
                                    @endcan
                                    
                                </td>
                                <!--                                <td>  Было
                                                 $order->division->name
                                            </td>-->
                                <td>
                                    @foreach ($allItems[$order->id] as $item)
                                        <div class="order-popup-parent" data-productid="{{ $item['productId'] }}">
                                            <p>{{ $item['name'] }}</p>
                                            <div class="order-popup-child">

                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="" class=" mx-auto  d-block popup-child-img" height="150">

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
                <div id="chartContainer" class="highChart"></div>
                <div id="chartContainer-1" class="highChart"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
<!--    <script src="/assets/js/checkBoxesOrdersList.js"></script>-->
    <script src="/assets/js/ordersIndex.js"></script>
@endpush
