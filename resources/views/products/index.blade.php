@extends('layouts.base')

@section('title_page', 'Продукты')

@push('styles-plugins')
    <!-- DataTables -->
    <link href="/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
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
                    <div class="filters mb-4">
                        <select name="" id="companyFilter">
                            <option value="1">Имя</option>
                            <option value="1">Имя</option>
                            <option value="1">Имя</option>
                        </select>

                        <select name="" id="categoryFilter">
                            <option value="1">Имя</option>
                            <option value="1">Имя</option>
                            <option value="1">Имя</option>
                        </select>

                        <div>
                            <p>
                                ККО
                            </p>

                            <input type="checkbox" name="" id="">
                        </div>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; padding-bottom: 12px">
                        <thead>
                            <tr>
                                <th rowspan="2">Название</th>
                                <th rowspan="2">Компания</th>
                                <th rowspan="2">Категория</th>
                                @can('update', App\Models\Product::class)
                                    <th rowspan="2">
                                        Количество
                                    </th>
                                @endcan
                                @can('update', App\Models\Product::class)
                                    <th rowspan="2">
                                        В резерве
                                    </th>
                                @endcan
                                <th colspan="4" class="text-center">
                                    ККО
                                </th>
                                <th colspan="2" class="text-center">
                                    Экспресс
                                </th>
                                <th rowspan="2">Действия</th>
                            </tr>
                            <tr>
                                {{-- ККО --}}
                                <th>
                                    Оперзал
                                </th>
                                <th>
                                    Открытие счетов
                                </th>
                                <th>
                                    Менеджерам
                                </th>
                                <th>
                                    Операционистам
                                </th>
                                {{-- Экспресс --}}
                                <th>
                                    Оперзал
                                </th>
                                <th>
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
                                    <td>{{ $product->companyName }}</td>
                                    <td>{{ $product->categoryName }}</td>
                                    @can('update', $product)
                                        <td>{{ $product->total_quantity }}</td>
                                    @endcan
                                    @can('update', $product)
                                        <td>{{ $product->total_reserved }}</td>
                                    @endcan
                                    {{-- ККО --}}
                                    <td>
                                        {!! kko_express_check($product->kko_hall) !!}
                                    </td>
                                    <td>
                                        {!! kko_express_check($product->kko_account_opening) !!}
                                    </td>
                                    <td>
                                        {!! kko_express_check($product->kko_manager) !!}
                                    </td>
                                    <td>
                                        {!! kko_express_check($product->kko_operator) !!}
                                    </td>
                                    {{-- Экспресс --}}
                                    <td>
                                        {!! kko_express_check($product->express_hall) !!}
                                    </td>
                                    <td>
                                        {!! kko_express_check($product->express_operator) !!}
                                    </td>
                                    <td>
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
                                    </td>
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
