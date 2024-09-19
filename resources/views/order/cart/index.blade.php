@extends('layouts.base')

@section('title_page', 'Корзина')

@push('styles-plugins')
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
                        <x-form action="{{ route('basket.clear') }}" method="POST">
                            <button class="btn btn-danger">Очистить корзину</button>
                        </x-form>

                        <table class="table mb-0 table-responsive">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Наименование</th>
                                    <th class="border-top-0">даты выпуска, разрешенные к рапространению</th>
                                    <th class="border-top-0">Категория</th>
                                    <th class="border-top-0">Количество</th>
                                    <th class="border-top-0">Действия</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('/storage/' . $product->image) }}" alt=""
                                                height="36">
                                            <p class="d-inline-block align-middle mb-0">
                                                <a href=""
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
                                            <input class="form-control form-control-sm w-30" type="number"
                                                value="{{ $product->pivot->quantity }}" id="quantity">
                                        </td>
                                        <td>

                                            <a href="{{ route('basket.remove', $product) }}" class="text-dark"><i
                                                    class="mdi mdi-close-circle-outline font-18"></i></a>

                                            {{-- <a href="" class="text-dark"><i
                                                    class="mdi mdi-close-circle-outline font-18"></i></a> --}}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="row justify-content-center">
                        <div class="col-md-6 align-self-center">
                            <div class="text-center cart-promo">
                                <img src="assets/images/logo-sm.png" alt="" height="50" class="mb-2">
                                <h4 class="">Have a promo code ?</h4>
                                <p class="font-13">If you have a promocode, You can take discount !</p>
                                <div class="input-group w-75 mx-auto">
                                    <input type="text" class="form-control  form-control-sm" placeholder="Use Promocode"
                                        aria-describedby="button-addon2">
                                    <button class="btn btn-primary btn-sm" type="button" id="button-addon2">Apply</button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="" class="apps-ecommerce-products.html"><i
                                                class="fas fa-long-arrow-alt-left me-1"></i> Continue Shopping</a>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a href="apps-ecommerce-checkout.html" class="">Checkout <i
                                                class="fas fa-long-arrow-alt-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                        <div class="col-md-6">
                            <div class="total-payment p-3">
                                <h6 class="header-title font-14">Total Payment</h6>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="payment-title">Subtotal</td>
                                            <td>$496.00</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title">Shipping</td>
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    <li>
                                                        <div class="radio radio-primary">
                                                            <input type="radio" name="radio" id="radio_1"
                                                                value="option_1" checked>
                                                            <label for="radio_1">
                                                                Shipping Charge : $5.00
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="radio radio-primary">
                                                            <input type="radio" name="radio" id="radio_2"
                                                                value="option_2">
                                                            <label for="radio_2">
                                                                Express Shipping Charge : $10.00
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="" class="text-dark"><strong>Change
                                                                Address</strong></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title">Promo Code</td>
                                            <td>-$10.00</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title">Total</td>
                                            <td class="text-dark"><strong>$491.00</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!--end col-->
                    </div><!--end row--> --}}
                </div><!--end card-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->

@endsection
