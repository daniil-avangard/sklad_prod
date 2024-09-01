@extends('layouts.base')

@push('styles-plugins')
        <!-- DataTables -->
        <link href="/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" /> 
@endpush

@section('content')
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Роли</h4>
                                        @include('includes.breadcrumb')
                                    </div><!--end col-->
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->

                            <x-success />

                           <div class="row">
                            <div class="col-12 mb-3">
                            <button type="button" class="col-auto align-self-center btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                    Добавить
                                </button>
                            </div>
                           </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">


                            <div class="card-body">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Роль</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($roles as $role)
                                       <tr>
                                           <td>{{ $role->id }}</td>
                                           <td>
                                            <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
                                            </td>
                                            <td>
                                                <x-form action="{{ route('roles.delete', $role) }}" method="DELETE">
                                                    <button onclick="return confirm('Вы уверены, что хотите удалить эту роль?')" type="submit" class="btn btn-danger">Удалить</button>
                                               </x-form>
                                            </td>
                                       </tr>
                                   @endforeach
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

                
               {{-- @include('includes.modal.add_role') --}} 

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