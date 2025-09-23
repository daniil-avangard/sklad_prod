@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
    'title' => 'Сборка',
    'route' => 'assembly',
    'breadcrumbs' => 'Сборка',
])

    <div class="row">
        <div class="col-12">

            <div class="table-responsive">

                <div class="block-filters-index">
                    <div class="order-filters">
                        <div class="filters-work-part">
                            <label for="productsOfOrders1" class="unclicked">Город:</label>
                            <div class="searchable">
                                <input class="index-top-filters" type="text" name="productsOfOrders1" id="divisiones-names" placeholder="Все">
                                <ul id="cities-list-data" class="dropdown__box-list">
                                    <li class="dropdown-item dropdown-item-new" data-productoption="Все">
                                        Все
                                    </li>
                                    @foreach ($groupDivisionsNames1 as $divisionName)
                                        <li class="dropdown-item dropdown-item-new" data-productoption="{{ $divisionName['name'] }}">
                                                {{ $divisionName['name'] }}
                                        </li>
                                    @endforeach
                                    
                                </ul>
                            </div>

                        </div>
                        <div class="filters-button-part">
                            <button class="select-work-buttons clean-filters">Очистить фильтры</button>
                        </div>
                    </div>
                    
                    <div class="order-filters">
                        <div class="filters-work-part">
                            <label for="statusOfOrder" class="unclicked">Статус:</label>
                            <select name='statusOfOrder' id='status-of-orders' class="index-top-filters">
                                <option value="">Все</option>
                                @foreach ($allOrdersStatus as $statusOrder)
                                    <option value="{{ $statusOrder['value'] }}">{{ $statusOrder['label'] }}</option>
                                @endforeach
                            </select>
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

@push('scripts-plugins')
    <script src="/assets/js/warehouseIndex.js"></script>
@endpush
