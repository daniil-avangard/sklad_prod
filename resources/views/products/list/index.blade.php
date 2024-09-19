@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Товары',
        'route' => 'products.list',
        'breadcrumbs' => 'Товары',
    ])



    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
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
                    </thead>
                    <tbody>

                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('/storage/' . $product->image) }}" alt="" height="40">
                                    <p class="d-inline-block align-middle mb-0">
                                        <a href="{{ route('products.info', $product) }}"
                                            class="d-inline-block align-middle mb-0 product-name">{{ $product->name }}</a>
                                        <br>
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

                                <x-form action="{{ route('basket.add', $product) }}" method="POST">
                                    <td>
                                        <input type="number" class="form-control form-control-sm" value="1"
                                            name="quantity">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" type="submit">Добавить в
                                            корзину</button>
                                    </td>
                                </x-form>
                            </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
