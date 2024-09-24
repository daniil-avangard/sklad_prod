@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Полномочия',
        'route' => 'permissions',
        'breadcrumbs' => 'Полномочия',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-body">
                        @if ($permissions->isEmpty())
                            Нет ни одной записи.
                        @else
                            <div class="table-responsive">
                                <table id="datatable" class="table table-borderless text-nowrap mb-0">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Название</th>
                                            <th>Событие</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    {{ $permission->id }}
                                                </td>
                                                <td>
                                                    {{ $permission->name }}
                                                </td>

                                                <td>
                                                    <a href="#">
                                                        {{ $permission->getName() }}
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                                        class="btn btn-primary">
                                                        Редактировать
                                                    </a>
                                                    <!-- <form action="" method="POST">
                                            @csrf

                                            <input type="hidden" name="permission_id" value="{{ $permission->id }}">

                                            <a href="#" onclick="event.preventDefault(); this.parentElement.submit();" class="text-danger small">
                                                Удалить
                                            </a>
                                        </form> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts-plugins')
        <!-- Required datatable js -->
        <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/plugins/datatables/dataTables.bootstrap5.min.js"></script>
        <script src="/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="/assets/pages/jquery.datatable.init.js"></script>
    @endpush
