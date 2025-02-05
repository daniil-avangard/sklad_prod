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
        'add_route' => $canCreateProduct ? 'products.create' : null
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Количество</th>
                                <th>В резерве</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                                    <td>{{ $product->total_quantity }}</td>
                                    <td>{{ $product->total_reserved }}</td>
                                    <td>
                                        @can('view', $product)
                                            <a href="{{ route('products.show', $product) }}"
                                                class="btn btn-primary">Посмотреть</a>
                                        @endcan
                                        @can('update', $product)
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="btn btn-warning">Изменить</a>
                                        @endcan
                                        @can('delete', $product)
                                            <x-form action="{{ route('products.delete', $product) }}" method="DELETE"
                                                style="display: inline-block;">
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этот продукт?');">Удалить</button>
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
