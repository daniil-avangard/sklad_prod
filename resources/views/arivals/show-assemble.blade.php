@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Сборка заказа №' . $order->id,
        'route' => 'assembly.show',
        'breadcrumbs' => 'Сборка',
        'param' => $order,
        'back_route' => 'assembly',
    ])

    <div class="row">
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Статус: <span
                                    class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}
                                </span>
                            </h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="card-body">
                    <button id="start-assembl" class="btn btn-primary" data-korobkaflag="{{ $flagKorobka }}" data-pk="{{ $order->id }}">Начать сборку</button>
                    <button class="btn btn-warning">Собран</button>
                    <button class="btn btn-danger">Отправлен</button>
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
                            <h2 class="card-title">Информация по сборке</h2>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>

                <div id="korobka-block-item" class="row assembly-korobka-row korobka-item-none">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>Коробка 1</td>
                                    <td>
                                        <label>Трек-номер</label>
                                        <input type="text" id="" name="korobka" value=''>
                                        <button class="add-track">Добавить</button>
                                        <button class="clean-track">Очистить</button>
                                    </td>
                                    <td>
                                        <button class="delete-korobka" data-pk="{{ $order->id }}">Удалить</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                
                @foreach ($korobkas as $korobka)
                    <div class="row assembly-korobka-row">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td>Коробка {{ $korobka->counter_number }}</td>
                                        <td>
                                            <label>Трек-номер</label>
                                            <input type="text" id="" name="korobka" value='{{ $korobka->track_number }}'>
                                            <button class="add-track">Добавить</button>
                                            <button class="clean-track">Очисить</button>
                                        </td>
                                        <td>
                                            <button class="delete-korobka" data-pk="{{ $korobka->id }}">Удалить</button>
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
    <script src="/assets/js/assemblyKorobka.js"></script>
@endpush
