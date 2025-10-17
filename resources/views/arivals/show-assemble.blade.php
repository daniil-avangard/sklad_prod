@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Фиксируем ширину таблиц сборки, чтобы они не «прыгали» */
        .assembly-korobka-row .table{width:100%; max-width:780px; margin:0 auto; table-layout:fixed;}
        .assembly-korobka-row .table td{vertical-align:middle;}
        /* Колонки как Row[SizedBox(100), Expanded(), SizedBox(200)] */
        .assembly-korobka-row .table td:nth-child(1){width:100px; white-space:nowrap;}
        .assembly-korobka-row .table td:nth-child(3){width:200px; white-space:nowrap;}
        .assembly-korobka-row .table td:nth-child(2){width:calc(100% - 300px);}        
        .assembly-korobka-row .korobka-actions-td{display:flex;gap:8px;align-items:center;justify-content:flex-end;white-space:nowrap;}
        .assembly-korobka-row .korobka-actions-td button{padding:4px 8px;font-size:12px;}
        /* Чтобы элементы не ломали макет */
        .assembly-korobka-row .shp-chk-small{max-width:140px;}
        .assembly-korobka-row .shp-chk-big{width:100%; max-width:none;}
        .assembly-korobka-row .shp-chk-lbl{margin-right:6px;}
        /* Убираем горизонтальный скролл, если появлялся */
        .assembly-korobka-row .table-responsive{overflow-x:hidden;}
        /* Ошибочное поле при проверке перед отгрузкой */
        .assembly-korobka-row input.invalid-input{background-color:#ffe6e6 !important; border:1px solid #dc3545 !important;}
        .assembly-korobka-row tr.invalid-row td{background-color:#ffe6e6 !important;}
    </style>
<!--    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>-->
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Сборка заказа №' . $order->id,
        'route' => 'assembly.show',
        'breadcrumbs' => 'Сборка',
        'param' => $order,
        'back_route' => 'assembly',
    ])

    <div id="info-about-order" class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Информация о заказчике</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body">
                    <p> <b>Подразделение: </b> {{ \App\Models\Division::find($order->user->division_id)->name }}</p>
                    <p> <b>ФИО: </b> {{ $order->user->surname }} {{ $order->user->first_name }}
                        {{ $order->user->middle_name }}
                    </p>
                    <p> <b>Должность: </b> {{ $order->user->position }}</p>
                    <p> <b>Телефон: </b> {{ $order->user->phone }}</p>
                    <p> <b>Email: </b> {{ $order->user->email }}</p>
                </div>
            </div>
        </div>
        <div class="col-4 empty-card-header">
            <div class="card">
                <div class="card-body">
                    <p> <b>Телефон: </b> {{ $order->user->phone }}</p>
                    <p> <b>Email: </b> {{ $order->user->email }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-9">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col" >
                            <h4 id="status-title" class="card-title">Статус: <span id="order-status" data-status="{{ $order->status->value }}"
                                    data-korobkaflag="{{ $flagKorobka }}" data-pk="{{ $order->id }}" class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}
                                </span>
                            </h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>

                <div class="card-body" style="padding-left: 5px !important; padding-right: 0;">  
                    <div class="buttons-orders-cotroller">
                        <div class="buttons-orders-elm">
                            <button id="start-assembl" class="btn btn-primary btn-margin" data-korobkaflag="{{ $flagKorobka }}" data-pk="{{ $order->id }}">Начать сборку</button>
                            <button id="package-assembled" class="btn btn-warning btn-margin">Собран</button>
                            <button id="package-shipped" class="btn btn-danger btn-margin">Отгружен</button>
                            <button id="status-back" class="btn btn-warning btn-margin">Статус Назад</button>
                        </div>
                        <div class="buttons-orders-elm">
                            <button id="print-order" class="btn btn-primary">
                                <img src="/assets/images/printer.svg" alt="logo-large" class="logo-lg logo-light">
                                <span style="margin-left: 5px;">Печать заказа</span>
                            </button>
                        </div>

                </div>
            </div>


            <div id="info-order-table" class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Даты актуализации</th>
                                    <th>Количество доступное к заказу</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            @php
                                                $dateOfActualities = $item->product->variants
                                                    ->where('is_active', true)
                                                    ->pluck('date_of_actuality')
                                                    ->unique()
                                                    ->values()
                                                    ->sortDesc();
                                            @endphp
                                            @foreach ($dateOfActualities as $dateOfActuality)
                                                @if (is_null($dateOfActuality))
                                                    <p class="m-0">Без даты</p>
                                                @else
                                                    <p class="m-0">
                                                        {{ \Carbon\Carbon::parse($dateOfActuality)->format('d.m.Y') }}
                                                    </p>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $item->product->variants->sum('quantity') - $item->product->variants->sum('reserved') }}
                                        </td>
                                        <td>
                                            @if (
                                                $order->status->value === \App\Enum\Order\StatusEnum::NEW->value ||
                                                    $order->status->value === \App\Enum\Order\StatusEnum::PROCESSING->value)
                                                <a
                                                    @can('updateQuantity', $order)
                                                    href="#"
                                                     class="quantity-input"
                                                        id="order_quantity_{{ $item->id }}" data-type="number"
                                                        data-pk="{{ $item->id }}" data-title="Введите количество"
                                                        @endcan>
                                                    {{ $item->quantity }}
                                                </a>
                                            @else
                                                {{ $item->quantity }}
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div id="info-order-additional" class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Дополнительная информация</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="row">
                    <div class="card-body col-6">
                        Комментарий заказчика:
                        <p class="m-0">{{ $order->comment }}</p>
                    </div>
                    <div class="card-body col-6">
                        Комментарий куратора:
                        <p class="m-0">
                            <a
                                @can('update', $order)
                            href="#" class="comments-manager" id="comments-manager" data-type="textarea"
                                data-pk="{{ $order->id }}" data-title="Введите комментарий"
                                @endcan>
                                {{ $order->comment_manager }}
                            </a>
                        </p>
                    </div>

                </div>

            </div>


            <div id="info-order-assemble" class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="card-title">Информация по сборке</h2>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>

                <div id="korobka-block-item" class="row assembly-korobka-row korobka-item-none">
                    <div class="table-responsive">
                            <table class="table table-bordered">
                            <colgroup>
                                <col style="width:100px">
                                <col>
                                <col style="width:200px">
                            </colgroup>
                            <thead></thead>
                            <tbody>
                                    <tr>
                                    <td>Коробка 1</td>
                                    <td>
                                        <label>Трек-номер</label>
                                        <input type="text" id="" name="korobka" value=''>
                                        <button type="button" class="clean-track">✕</button>
                                        <button type="button" class="add-track">✓</button>
                                    </td>
                                    <td class="korobka-actions-td">
                                        <button type="button" class="delete-korobka" data-pk="{{ $order->id }}">Удалить</button>
                                        <button type="button" class="copy-korobka" disabled>Дублировать</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                
                @if (count($korobkas) > 0)
                    <div class="buttons-orders-elm warehous-check" id="div-for-checked">
                        <input class="checkbox-filter-new btn-margin" type="radio" name="delivery-method" value="delivery-track" id="delivery-track">
                        <label for="delivery-track">Перевозчик</label>
                        <input class="checkbox-filter-new btn-margin" type="radio" name="delivery-method" value="delivery-kurier" id="delivery-kurier">
                        <label for="delivery-kurier">Курьер</label>
                        <input class="checkbox-filter-new btn-margin" type="radio" name="delivery-method" value="delivery-car" id="delivery-car">
                        <label for="delivery-car">Машина</label>
                        <input class="checkbox-filter-new btn-margin" type="radio" name="delivery-method" value="delivery-another" id="delivery-another">
                        <label for="delivery-another">Другое</label>
                    </div>
                @endif

                @foreach ($korobkas as $korobka)
                    <div class="row assembly-korobka-row">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <colgroup>
                                    <col style="width:100px">
                                    <col>
                                    <col style="width:200px">
                                </colgroup>
                                <thead></thead>
                            <table class="table table-bordered">
                                <colgroup>
                                    <col style="width:100px">
                                    <col>
                                    <col style="width:200px">
                                </colgroup>
                                <thead></thead>
                                <tbody>
                                    <tr data-method="{{ $korobka->delivery_method }}" data-track="{{ $korobka->track_number }}" data-courier-date="{{ $korobka->courier_date }}" data-courier-time="{{ $korobka->courier_time }}" data-car-number="{{ $korobka->car_number }}" data-car-date="{{ $korobka->car_date }}" data-other-comment="{{ $korobka->other_comment }}">
                                        <td>Коробка {{ $korobka->counter_number }}</td>
                                        <td>
                                            <label>Трек-номер</label>
                                            <input type="text" id="" name="korobka" value='{{ $korobka->track_number }}'>

                                            <button type="button" class="clean-track">✕</button>

                                            <button type="button" class="add-track">✓</button>

                                        </td>
                                        <td class="korobka-actions-td">
                                            <button type="button" class="delete-korobka" data-pk="{{ $korobka->id }}">Удалить</button>
                                            <button type="button" class="copy-korobka" data-pk="{{ $korobka->id }}" {{ $korobka->delivery_method ? '' : 'disabled' }}>Дублировать</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endforeach
                    <div class="row {{ $flagKorobka =='yes' ? 'korobka-item-show' : 'korobka-item-none'  }}" id="korobka-add-wrap">
                        <div class="card-body"><button id="korobka-add" class="btn btn-primary">Добавить коробку</button></div>
                    </div>
            </div>


        </div>
        
    </div>
    </div>
@endsection


@push('scripts-plugins')
    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>

<!--    <script src="/assets/pages/orders/update.quantity.js"></script>-->
    <script src="/assets/js/assemblyKorobka.js"></script>
@endpush
