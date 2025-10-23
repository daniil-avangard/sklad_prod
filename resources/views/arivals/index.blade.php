@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
    'title' => 'Приходы',
    'route' => 'arivals',
    'breadcrumbs' => 'Приходы',
    'add_route' => $canCreateArival ? 'arivals.create' : null
])

<div class="row">
    <div class="col-12" style="margin-bottom: 10px;">
        <div class="block-filters-index">
            <div class="order-filters">
                <div class="filters-work-part">
                    <label for="productsOfOrders1" class="unclicked">ID:</label>
                    <div class="searchable">
                        <input class="index-top-filters" type="text" name="productsOfOrders1" id="idOfOrders" placeholder="Все">
                        <ul id="id-list-data" class="dropdown__box-list">
                            <li class="dropdown-item dropdown-item-new" data-productoption="Все">
                                Все
                            </li>
                            @foreach ($arivals as $arival)
                                <li class="dropdown-item dropdown-item-new" data-productoption="{{ $arival->id }}">
                                        {{ $arival->id }}
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
                    <label for="productsOfOrders1" class="unclicked">Пользователь:</label>
                    <div class="searchable">
                        <input class="index-top-filters" type="text" name="productsOfOrders1" id="divisiones-names" placeholder="Все">
                        <ul id="cities-list-data" class="dropdown__box-list">
                            <li class="dropdown-item dropdown-item-new" data-productoption="Все">
                                Все
                            </li>
                            @foreach ($allArivalsCreateUsers as $user)
                                <li class="dropdown-item dropdown-item-new" data-productoption="{{ $user['label'] }}">
                                        {{ $user['label'] }}
                                </li>
                            @endforeach

                        </ul>
                    </div>
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
            
            <div class="order-filters month-filters">

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
            </div>
            
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Номер</th>
                    <th>Дата поставки</th>
                    <th>Пользователь</th>
                    <th>Товары</th>
                    <th>Количество</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $flagforrow = "f";
                @endphp
                @php
                    $flagforrow1 = "f";
                @endphp
                @foreach ($arivals as $arival)
                <tr>
                    <td>{{ $arival->id }}</td>
                    <td>{{ $arival->invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($arival->arrival_date)->format('d.m.Y') }}</td>
                    <td>{{ $arival->user->surname }} {{ $arival->user->first_name }} {{ $arival->user->middle_name }}</td>
                    <td class="td-with-row-color">
                        @foreach ($arival->products as $item)
                            @if ($flagforrow == "f")
                            <p class="p-orders"><span>{{ $item->product->name }}</span></p>
                            @php
                            $flagforrow = "t";
                            @endphp
                            @else
                            <p class="p-orders order-index-row"><span>{{ $item->product->name }}</span></p>
                            @php
                            $flagforrow = "f";
                            @endphp
                            @endif
                        @endforeach
                    </td>
                    <td class="td-with-row-color">
                        @foreach ($arival->products as $item)
                            @if ($flagforrow1 == "f")
                            <p class="p-orders-quant"><span>{{ $item->quantity }}</span></p>
                            @php
                            $flagforrow1 = "t";
                            @endphp
                            @else
                            <p class="p-orders-quant order-index-row"><span>{{ $item->quantity }}</span></p>
                            @php
                            $flagforrow1 = "f";
                            @endphp
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <span class="badge bg-{{ $arival->status->color() }}">
                            {{ $arival->status->name() }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('arivals.show', $arival->id) }}" class="btn btn-primary">Посмотреть</a>
                        @if($arival->status === \App\Enum\ArivalStatusEnum::pending)
                            @can('changeStatus', $arival)
                                <form action="{{ route('arivals.accepted', $arival->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $arival->id }}">
                                    <button type="submit" class="btn btn-success">Принять</button>
                                </form>
                                <form action="{{ route('arivals.rejected', $arival->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $arival->id }}">
                                    <button type="submit" class="btn btn-danger">Отклонить</button>
                                </form>
                            @endcan
                        @endif
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts-plugins')
    <script src="/assets/js/arrivalIndex.js"></script>
@endpush
