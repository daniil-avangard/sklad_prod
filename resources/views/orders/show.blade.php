@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказ №' . $order->id,
        'route' => 'orders.show',
        'breadcrumbs' => 'Заказы',
        'param' => $order,
        'back_route' => 'orders.new',
    ])

    @php
        $type = "";
    @endphp


    <div class="row">
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 id="status-order" data-pk="{{ $order->id }}" class="card-title">Статус: <span
                                    class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}
                                </span>
                            </h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="card-header">
                    <h4 class="card-title">Дата заказа: <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                </span></h4>
                </div>
                <div class="card-body">
                    @can('processingStatus', $order)
                        @if ($currentStatus === 'new')
                            <a id="process-to-another" class="btn btn-primary" href="{{ route('orders.status.processing', $order) }}">Проверено
                                куратором</a>
                        @endif
                    @endcan
                    @can('managerProcessingStatus', $order)
                        @if ($currentStatus === 'processing')
                            <a class="btn btn-primary" href="{{ route('orders.status.manager-processing', $order) }}">
                                Проверено
                            начальником кураторов
                            </a>
                        @endif
                    @endcan
                    @can('transferToWarehouse', $order)
                        @if ($currentStatus === 'manager_processing')
                            <a class="btn btn-warning" href="{{ route('orders.status.transferred-to-warehouse', $order) }}">Передать
                                на склад</a>
                        @endif
                    @endcan
                    @can('canceledStatus', $order)
                        @if ($currentStatus !== 'shipped' && $currentStatus !== 'delivered')
                            <a class="btn btn-danger" href="{{ route('orders.status.canceled', $order) }}">Отменить</a>
                        @endif
                    @endcan
                    @can('create', $order)
                        @if ($currentStatus === 'shipped')
                            <button id="package-shipped" class="btn btn-primary">Заказ доставлен</button>
                        @endif
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-order" class="table table-bordered">
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
                                        <td class="product-select">{{ $item->product->name }}</td>
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
                                                @endif
                                                <p class="m-0">
                                                    {{ \Carbon\Carbon::parse($dateOfActuality)->format('d.m.Y') }}</p>
                                            @endforeach
                                        </td>
                                        <td>{{ $item->product->variants->sum('quantity') - $item->product->variants->sum('reserved') }}
                                        </td>
                                        <td>
                                            @if ($order->status->value === \App\Enum\Order\StatusEnum::NEW->value)
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


            <div class="card">
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
            
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Добавить товар</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="row">
                    <div class="card-body col-sm-3">
                        <label class="form-label">Товары</label>
                        <select id="product-name" name="products" class="form-select select2 product-select-option">
                            <option value="Выберите товар">Выберите товар</option>
                            @foreach ($allGoodsInOrders as $product)
                                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body col-sm-3">
                            <label class="form-label">Количество</label>
                            <input id="product-quontity" type="text" name="products" value="0" class="form-control">
                    </div>
                    <div class="card-body col-sm-3">
                            <label class="form-label"></label>
                            <div class="col-sm-12">
                                <span id="add-item" data-repeater-create="" class="btn btn-outline-secondary">
                                    <span class="fas fa-plus"></span> Добавить
                                </span>
                            </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-3">
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
    </div>
    <div id="myModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Доставка</h4>
                    <span id="close-modal" class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <h3>Информация по доставке</h3>
                    <textarea></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="ok-comment" class="btn btn-info" data-dismiss="modal">Все хорошо</button>
                    <button id="big-comment" type="button" class="btn btn-warning">Другой Комментарий</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
@endsection


@push('scripts-plugins')
    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>
    <script src="/assets/pages/orders/update.quantity.js"></script>
    <script src="/assets/js/packageshipped.js"></script>
@endpush
