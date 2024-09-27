@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказ №' . $order->id,
        'route' => 'orders.edit',
        'breadcrumbs' => 'Заказы',
        'param' => $order,
        'back_route' => 'orders',
    ])


    <div class="row">
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Статус: {{ $order->status->name() }}
                            </h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="card-body">
                    <a class="btn btn-primary" href="#" target="_blank">Проверено куратором</a>
                    <a class="btn btn-success" href="#" target="_blank">Одобрено</a>
                    <a class="btn btn-warning" href="#" target="_blank">Передать на склад</a>
                    <a class="btn btn-danger" href="#" target="_blank">Отменить</a>
                </div>
            </div>

            <div class="card">
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
                                                @endif
                                                <p class="m-0">
                                                    {{ \Carbon\Carbon::parse($dateOfActuality)->format('d.m.Y') }}</p>
                                            @endforeach
                                        </td>
                                        <td>{{ $item->product->variants->sum('quantity') - $item->product->variants->sum('reserved') }}
                                        </td>
                                        <td>
                                            <a href="#" class="editable-input" id="order_quantity_{{ $item->id }}"
                                                data-type="number" data-pk="{{ $item->id }}"
                                                data-title="Введите количество"
                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">{{ $item->quantity }}</a>
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
                        Комментарий заказчика: {{ $order->comment }}
                    </div>
                    <div class="card-body col-6">
                        Комментарий куратора: {{ $order->comment }}
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
@endsection


@push('scripts-plugins')
    <script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>

    <script src="/assets/pages/orders/update.quantity.js"></script>
@endpush
