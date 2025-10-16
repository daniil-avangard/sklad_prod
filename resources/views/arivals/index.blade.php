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
                                <li class="dropdown-item dropdown-item-new" data-productoption="{{ $user['value'] }}">
                                        {{ $user['label'] }}
                                </li>
                            @endforeach

                        </ul>
                    </div>

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
                    <th>Статус</th>
                    <th>Дата создания</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($arivals as $arival)
                <tr>
                    <td>{{ $arival->id }}</td>
                    <td>{{ $arival->invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($arival->arrival_date)->format('d.m.Y') }}</td>
                        <td>{{ $arival->user->surname }} {{ $arival->user->first_name }} {{ $arival->user->middle_name }}</td>
                    <td>
                        <span class="badge bg-{{ $arival->status->color() }}">
                            {{ $arival->status->name() }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($arival->created_at)->format('d.m.Y H:i:s') }}</td>
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
