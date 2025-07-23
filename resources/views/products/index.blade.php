@extends('layouts.base')

@section('title_page', 'Продукты')

@push('styles-plugins')
    <!-- DataTables -->
    <link href="/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="/assets/css/newmodelscomponent.css" rel="stylesheet">
    <!-- Responsive datatable examples -->
    <link href="/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Продукты',
        'route' => 'products',
        'breadcrumbs' => 'Продукты',
        'add_route' => $canCreateProduct ? 'products.create' : null,
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="overflow-x: auto;">
                    {{-- Фильтра --}}
                    <div class="filters mb-4" id="product-table-filters">
                        <div class="d-flex justify-content-between align-middle mb-3">
                            <div class="d-flex">
                                <!-- Фильтр по компании -->
                                <label class="d-flex align-items-center me-4" for="companyFilter">
                                    <span class="me-2">
                                        Компания:
                                    </span>

                                    <select class="form-select" name="companyFilter" id="companyFilter">
                                        <option value="all" selected>Все</option>
                                        @foreach ($productCompanies as $productCompany)
                                            <option value="{{ $productCompany->name }}">{{ $productCompany->name }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <!-- Фильтр по категории -->
                                <label class="d-flex align-items-center" for="categoryFilter">
                                    <span class="me-2">
                                        Категория:
                                    </span>

                                    <select class="form-select" name="categoryFilter" id="categoryFilter">
                                        <option value="all" selected>Все</option>
                                        @foreach ($productCategories as $productCategory)
                                            <option value="{{ $productCategory->name }}">{{ $productCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div class="d-flex">
                                <button id="chanel-filter-button" class="button btn-primary btn-sm p-1 ps-2 pe-2 border-0 btn-products-colors">
                                    Фильтр по каналам распространения

                                    <span class="ms-1">
                                        <i data-feather='plus-circle' style='color: #fff; width: 14px; height: 14px'></i>
                                    </span>
                                </button>

                                <button id="reset-product-table-button"
                                    class="button btn-danger btn-sm p-1 ps-2 pe-2 border-0 btn-basket-prod-colors" style="margin-left: 10px;">
                                    Сбросить
                                </button>
                            </div>
                        </div>

                        <div id="chanel-filters" class="visually-hidden d-flex columns-2 gap-2 mb-4 ms-0 me-0">
                            <div class="card col-6 mb-0 p-0">
                                <div class="card-header p-2">
                                    <h4 class="card-title">ККО</h4>
                                </div>

                                <div class="card-body p-2 py-3">
                                    <div class="row">
                                        <div class="form-group col-6 mb-0">
                                            <div class="checkbox-primary">
                                                <input class="checkbox-filter" id="kko_hall" type="checkbox" value="1"
                                                    name="kko_hall">
                                                <label for="kko_hall">
                                                    Оперзал
                                                </label>
                                            </div>

                                            <div class="checkbox-primary">
                                                <input class="checkbox-filter" id="kko_account_opening" type="checkbox"
                                                    value="1" name="kko_account_opening">
                                                <label for="kko_account_opening">
                                                    Открытие счетов
                                                </label>
                                            </div>

                                            <div class="checkbox-primary">
                                                <input class="checkbox-filter" id="kko_manager" type="checkbox"
                                                    value="1" name="kko_manager">
                                                <label for="kko_manager">
                                                    Менеджеру
                                                </label>
                                            </div>

                                        </div>

                                        <div class="form-group col-6 mb-0">
                                            <label for="kko_operator">Операционист</label>

                                            <select id="kko_operator" name="kko_operator" class="form-select">
                                                <option value="all" selected>Все</option>
                                                @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                    <option {{ old('kko_operator') == $operator->value ? 'selected' : '' }}
                                                        value="{{ $operator->value }}">
                                                        {{ $operator->name() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card col-6 mb-0 p-0">
                                <div class="card-header p-2">
                                    <h4 class="card-title">Экспресс</h4>
                                </div>

                                <div class="card-body p-2 py-3">
                                    <div class="row">
                                        <div class="form-group col-6 mb-0">
                                            <div class="checkbox-primary">
                                                <input class="checkbox-filter" id="express_hall" type="checkbox"
                                                    value="1" name="express_hall">
                                                <label for="express_hall">
                                                    Оперзал
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group col-6 mb-0">
                                            <label for="express_operator">Операционист</label>

                                            <select id="express_operator" name="express_operator" class="form-select">
                                                <option value="all" selected>Все</option>
                                                @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                    <option
                                                        {{ old('express_operator') == $operator->value ? 'selected' : '' }}
                                                        value="{{ $operator->value }}">
                                                        {{ $operator->name() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Таблица --}}
                    <table id="product-table" class="table table-striped table-bordered nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center bckgrnd-table-cell-2">Название</th>
                                <th rowspan="2" class="text-center bckgrnd-table-cell-2">Компания</th>
                                <th colspan="4" class="text-center bckgrnd-table-cell-2">
                                    ККО
                                </th>
                                <th colspan="2" class="text-center bckgrnd-table-cell-2">
                                    Экспресс
                                </th>
<!--                                <th rowspan="2" class="text-center bckgrnd-table-cell-2">Действия</th>-->
                            </tr>
                            <tr>
                                {{-- ККО --}}
                                <th class="bckgrnd-table-cell-2">
                                    Оперзал
                                </th>
                                <th class="bckgrnd-table-cell-2">
                                    Открытие счетов
                                </th>
                                <th class="bckgrnd-table-cell-2">
                                    Менеджерам
                                </th>
                                <th class="bckgrnd-table-cell-2">
                                    Операционистам
                                </th>
                                {{-- Экспресс --}}
                                <th class="bckgrnd-table-cell-2">
                                    Оперзал
                                </th>
                                <th class="bckgrnd-table-cell-2">
                                    Операционистам
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                                    </td>
                                    <td class="">{{ $product->companyName }}</td>
                                    {{-- ККО --}}
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->kko_hall }}>
                                        {!! kko_express_check($product->kko_hall) !!}
                                    </td>
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->kko_account_opening }}>
                                        {!! kko_express_check($product->kko_account_opening) !!}
                                    </td>
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->kko_manager }}>
                                        {!! kko_express_check($product->kko_manager) !!}
                                    </td>
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->kko_operator }}>
                                        {!! kko_express_check($product->kko_operator) !!}
                                    </td>
                                    {{-- Экспресс --}}
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->express_hall }}>
                                        {!! kko_express_check($product->express_hall) !!}
                                    </td>
                                    <td class="bckgrnd-table-cell-1" data-search={{ $product->express_operator }}>
                                        {{-- {{ $product->express_operator }} --}}
                                        {!! kko_express_check($product->express_operator) !!}
                                    </td>
                                    <!--<td>
                                        @can('view', $product)
                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-primary button-icon-wrapper">
                                                <i data-feather="eye" class="align-self-center topbar-icon button-icon"></i>
                                            </a>
                                        @endcan
                                        @can('update', $product)
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="btn btn-warning button-icon-wrapper">
                                                {{-- Изменить --}}
                                                <i data-feather="edit" class="align-self-center topbar-icon button-icon"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $product)
                                            <x-form action="{{ route('products.delete', $product) }}" method="DELETE"
                                                style="display: inline-block;">
                                                <button type="submit" class="btn btn-danger button-icon-wrapper"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этот продукт?');">
                                                    {{-- Удалить --}}
                                                    <i data-feather="trash"
                                                        class="align-self-center topbar-icon button-icon"></i>
                                                </button>
                                            </x-form>
                                        @endcan
                                    </td>-->
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts-plugins')
    <!-- Required datatable js -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap5.min.js"></script>
    <!-- Buttons examples -->
    <script src="/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="/plugins/datatables/buttons.bootstrap5.min.js"></script>
    <script src="/plugins/datatables/jszip.min.js"></script>
    <script src="/plugins/datatables/pdfmake.min.js"></script>
    <script src="/plugins/datatables/vfs_fonts.js"></script>
    <script src="/plugins/datatables/buttons.html5.min.js"></script>
    <script src="/plugins/datatables/buttons.print.min.js"></script>
    <script src="/plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="/plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="/assets/pages/jquery.datatable.init.js"></script>
@endpush
