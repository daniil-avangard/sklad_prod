@extends('layouts.base')

@push('styles-plugins')
        <!-- DataTables -->
        <link href="/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" /> 
@endpush

@section('content')
@include('includes.breadcrumb', ['title' => 'Пользователи', 'route' => 'users.index', 'breadcrumbs' => 'Пользователи'])



                <div class="row">
                    <div class="col-12">
                        <div class="card">


                            <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Фамилия</th>
                                        <th>Имя</th>
                                        <th>Отчество</th>
                                        <th>Email</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td><a href="{{ route('users.show', $user->id) }}">{{ $user->surname }}</a></td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->middle_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @can('update', $user)
                                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Редактировать</a>
                                                @endcan
                                                @can('delete', $user)
                                                    <form action="{{ route('users.delete', $user->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">Удалить</button>
                                                    </form>
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