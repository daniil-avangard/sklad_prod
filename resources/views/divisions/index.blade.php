@extends('layouts.base')

@push('styles-plugins')
    <!-- DataTables -->
    <link href="/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Подразделения',
        'route' => 'divisions',
        'breadcrumbs' => 'Подразделения',
        'add_route' => $canCreateProduct ? 'divisions.create' : null,
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Категория</th>
                                <th>Город</th>
                                <th>Отдел</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divisions as $division)
                                <tr>
                                    <td>{{ $division->id }}</td>
                                    <td>{{ $division->division_category }}</td>
                                    <td>{{ $division->city }}</td>
                                    <td>{{ $division->name }}</td>
                                    <td class="">
                                        <a href="{{ route('divisions.show', $division) }}"
                                            class="btn btn-primary button-icon-wrapper">
                                            <i data-feather="eye" class="align-self-center topbar-icon button-icon"></i>
                                        </a>
                                        @can('update', \App\Models\Product::class)
                                            <a href="{{ route('divisions.show', $division) }}"
                                                class="btn btn-warning button-icon-wrapper">
                                                <i data-feather="edit" class="align-self-center topbar-icon button-icon"></i>
                                            </a>
                                        @endcan
                                        @can('delete', \App\Models\Product::class)
                                            <x-form action="{{ route('divisions.delete', $division) }}" method="DELETE"
                                                style="display: inline-block;">
                                                <button onclick="return confirm('Вы уверены?')" type="submit"
                                                    class="btn btn-danger button-icon-wrapper">
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
