@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Группа подразделений',
        'route' => 'groups.divisions.show',
        'breadcrumbs' => 'Группы подразделений',
    ])

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body  report-card">
                            <div class="row">
                                <div class="col d-flex justify-content-between">
                                    <p class="text-dark mb-1 fw-semibold">Список подразделений</p>
                                    @can('create', App\Models\Product::class)
                                    <a href="{{ route('groups.divisions.division.create', $group) }}"
                                        class="col-auto align-self-center btn btn-primary">
                                        Добавить
                                    </a>
                                    @endcan
                                </div>

                                <table id="permissions-table" class="bootstable table-responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Полномочия</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($divisions as $division)
                                            <tr>
                                                <td>{{ $division->id }}</td>
                                                <td>{{ $division->name }}</td>
                                                <td>
                                                    @can('create', App\Models\Product::class)
                                                    <x-form
                                                        action="{{ route('groups.divisions.division.detach', [$group, $division]) }}"
                                                        method="POST" style="display: inline-block;">
                                                        <input type="hidden" name="division_id"
                                                            value="{{ $division->id }}">
                                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                                    </x-form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

            </div><!--end col-->
        </div><!--end row-->


    </div><!--end col-->
    </div><!--end row-->
@endsection
