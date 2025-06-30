@extends('layouts.base')
<!--@vite(['resources/css/app.css', 'resources/js/app.js'])-->
@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Товары',
        'route' => 'products.list',
        'breadcrumbs' => 'Товары',
    ])

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <div class="table-container-goods">
                    <table id="datatable" class="table table-bordered">
                        <tr>
                            <th class="text-center orders_picture_row" rowspan="2">Изображение</th>
                            <th class="text-center" rowspan="2">Название</th>
                            <th class="text-center tbl-borders-right" rowspan="2">Даты актуализации</th>
                            <th class="text-center tbl-borders-right tbl-borders-top bckgrnd-table-cell" colspan="4">KKO</th>
                            <th class="text-center tbl-borders-right tbl-borders-top bckgrnd-table-cell" colspan="2">Экспресс</th>
                            <th class="text-center" colspan="2">Действия</th>
                        </tr>

                        <tr>
                            <th class="text-center bckgrnd-table-cell" colspan="1">Оперзал</th>
                            <th class="text-center bckgrnd-table-cell" colspan="1">Открытие счетов</th>
                            <th class="text-center bckgrnd-table-cell" colspan="1">Менеджерам</th>
                            <th class="text-center tbl-borders-right bckgrnd-table-cell" colspan="1">Операционистам</th>
                            <th class="text-center bckgrnd-table-cell" colspan="1">Оперзал</th>
                            <th class="text-center tbl-borders-right bckgrnd-table-cell" colspan="1">Операционистам</th>
                            <th class="text-center" colspan="1">Количество</th>
                            <th class="text-center" colspan="1"></th>
                        </tr>

                        @foreach ($products as $product)
                            <tr>
                                <td class="orders_picture_row">
                                    <img src="{{ asset('/storage/' . $product->image) }}" alt="" height="40" class="popup-child-img">
                                </td>
                                <td>
                                    <p class="d-inline-block align-middle mb-0">
<!--                                        @can('view', \App\Models\Product::class)-->
                                            <a href="{{ route('products.info', $product) }}"
                                                class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</a>
                                            <br>
                                            <span class="text-muted font-13">{{ $product->sku }}</span>
<!--                                        @else
                                            <span
                                                class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</span>
                                            <br>
                                        @endcan-->

<!--                                        <span class="text-muted font-13">{{ $product->sku }}</span>-->
                                    </p>
                                </td>
<!--                                <td>{{ $product->category->name }}</td>-->
                                <td class="tbl-borders-right">
                                    @php
                                        $dateOfActualities = $product->variants
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
                                <td class="bckgrnd-table-cell">{!! kko_express_check($product->kko_hall) !!}</td>
                                <td class="bckgrnd-table-cell">{!! kko_express_check($product->kko_account_opening) !!}</td>
                                <td class="bckgrnd-table-cell">
                                    {!! kko_express_check($product->kko_manager) !!}
                                </td>
                                <td class="tbl-borders-right bckgrnd-table-cell">
                                    {!! kko_express_check($product->kko_operator) !!}
                                </td>
                                <td class="bckgrnd-table-cell">
                                    {!! kko_express_check($product->express_hall) !!}
                                </td>
                                <td class="tbl-borders-right bckgrnd-table-cell">
                                    {!! kko_express_check($product->express_operator) !!}
                                </td>

                                <x-form class="add-product-to-basket-form" data-product-id="{{ $product->id }}"
                                    action="{{ route('basket.add', $product) }}" method="POST" autocomplete="off">
                                    @csrf

                                    <td>
                                        <input type="number" class="form-control form-control-sm" placeholder="0"
                                            min="0" name="quantity" autocomplete="off" required>
                                    </td>
                                    <td>
                                        @if ($arrayProductsInBasket[$product->id] == 0)
                                        <button class="btn btn-primary" type="submit">
                                            Добавить в
                                            корзину
                                        </button>
                                        @else
                                        <button class="btn btn-primary basket-button-change" type="submit">
                                            {{ $arrayProductsInBasket[$product->id] }} добавлено в
                                            корзину
                                        </button>
                                        @endif
                                    </td>
                                </x-form>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="buttons-orders-cotroller">
                <div class="buttons-orders-elm">
                    <button id="redirect-to-basket" class="btn btn-success mb-3">Перейти в корзину</button>
                </div>
                <div class="buttons-orders-elm">
                    <button id="all-items-to-basket" class="btn btn-primary mb-3">Добавить все товары в корзину</button>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection


{{-- @push('scripts-plugins')
    <script src="/assets/pages/product/add-product-to-basket.js"></script>
@endpush --}}
