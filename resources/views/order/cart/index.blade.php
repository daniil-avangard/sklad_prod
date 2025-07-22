@extends('layouts.base')

@section('title_page', 'Корзина')

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    @include('includes.breadcrumb', [
        'title' => 'Корзина',
        'route' => 'basket',
        'breadcrumbs' => 'Корзина',
    ])

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive shopping-cart">
                        
                        <div class="table-container">
                        <table class="table table-bordered">
                            
                                <tr>
                                    <th class="border-top-0 orders_picture_row">Изображение</th>
                                    <th class="border-top-0">Наименование</th>
                                    <th class="border-top-0">даты выпуска, разрешенные к рапространению</th>
                                    <th class="border-top-0">Категория</th>
                                    <th class="border-top-0">Количество</th>
                                    <th class="border-top-0">Действия</th>
                                </tr>
                            

                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('/storage/' . $product->image) }}" alt=""
                                                height="36" class="popup-child-img">
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0">
                                                <a href="{{ route('products.info', $product) }}"
                                                    class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</a>
                                                <br>
                                                <span class="text-muted font-13">{{ $product->sku }}</span>
                                            </p>
                                        </td>
                                        <td>
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
                                        <td>
                                            {{ $product->category->name }}
                                        </td>
                                        <td>
                                            <x-form class="add-product-to-basket-form" action="{{ route('basket.update', $product) }}" method="POST">
                                                <div class="buttons-orders-cotroller">
                                                <div class="buttons-orders-elm">
                                                <input class="form-control form-control-sm w-70" type="number"
                                                    name="quantity" value="{{ $product->pivot->quantity }}" min="1"
                                                    id="quantity">
                                                </div>
                                                    <div class="buttons-orders-elm buttons-orders-cotroller-1">
                                                <button type="submit" class="btn btn-primary btn-sm update-button">
                                                    <img src="/assets/images/update_svg.svg" alt="logo-large" class="logo-lg logo-light update-button">
                                                </button>
                                                    </div>
                                                </div>
                                            </x-form>
                                        </td>
                                        <td>

                                            <!--<a href="{{ route('basket.remove', $product) }}" class="text-dark"><i
                                                    class="mdi mdi-close-circle-outline font-18"></i></a> -->
                                            <button data-productid="{{$product->id}}" class="btn btn-danger btn-sm delete-from-basket basket-button-change-1">Удалить</button>

                                            {{-- <a href="" class="text-dark"><i
                                                    class="mdi mdi-close-circle-outline font-18"></i></a> --}}
                                        </td>
                                    </tr>
                                @endforeach

                            
                        </table>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 align-self-center">

                            <div class="mt-4">
                                <x-form id="save-order-form" action="{{ route('basket.saveorder') }}" method="POST">
                                    <textarea name="comment" class="form-control" placeholder="Комментарий к заказу"></textarea>
                                    <button class="btn btn-primary mt-2">Отправить заказ</button>
                                </x-form>
                            </div>
                            <div class="mt-4">
                                <x-form action="{{ route('basket.clear') }}" method="POST">
                                    <button class="btn btn-danger">Очистить корзину</button>
                                </x-form>
                            </div>
                        </div><!--end col-->
                        <div class="col-md-6">

                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->

@endsection

@push('scripts-plugins')
<script src="/assets/js/workWithBasket.js"></script>
@endpush
