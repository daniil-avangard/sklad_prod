@extends('layouts.base')

@push('styles-plugins')
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
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
                            <th class="text-center" rowspan="2">Изображение</th>
                            <th class="text-center" rowspan="2">Название</th>
                            <th class="text-center" rowspan="2">Категория</th>
                            <th class="text-center" colspan="4">KKO</th>
                            <th class="text-center" colspan="2">Экспресс</th>
                            <th class="text-center" colspan="2">Действия</th>
                        </tr>

                        <tr>
                            <th class="text-center" colspan="1">Оперзал</th>
                            <th class="text-center" colspan="1">Открытие счетов</th>
                            <th class="text-center" colspan="1">Менеджерам</th>
                            <th class="text-center" colspan="1">Операционистам</th>
                            <th class="text-center" colspan="1">Оперзал</th>
                            <th class="text-center" colspan="1">Операционистам</th>
                            <th class="text-center" colspan="1">Количество</th>
                            <th class="text-center" colspan="1"></th>
                        </tr>

                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('/storage/' . $product->image) }}" alt="" height="40">
                                </td>
                                <td>
                                    <p class="d-inline-block align-middle mb-0">
                                        @can('view', \App\Models\Product::class)
                                            <a href="{{ route('products.info', $product) }}"
                                                class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</a>
                                            <br>
                                            <span class="text-muted font-13">{{ $product->sku }}</span>
                                        @else
                                            <span
                                                class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</span>
                                            <br>
                                        @endcan

                                        <span class="text-muted font-13">{{ $product->sku }}</span>
                                    </p>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>{!! kko_express_check($product->kko_hall) !!}</td>
                                <td>{!! kko_express_check($product->kko_account_opening) !!}</td>
                                <td>
                                    {!! kko_express_check($product->kko_manager) !!}
                                </td>
                                <td>
                                    {!! kko_express_check($product->kko_operator) !!}
                                </td>
                                <td>
                                    {!! kko_express_check($product->express_hall) !!}
                                </td>
                                <td>
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
                                        <button class="btn btn-primary" type="submit">
                                            Добавить в
                                            корзину
                                        </button>
                                    </td>
                                </x-form>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <button id="redirect-to-basket" class="btn btn-success mb-3">Перейти в корзину</button>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection


{{-- @push('scripts-plugins')
    <script src="/assets/pages/product/add-product-to-basket.js"></script>
@endpush --}}
