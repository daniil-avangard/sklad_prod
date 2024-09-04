@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Подразделения', 
    'route' => 'divisions', 
    'breadcrumbs' => 'Подразделения',
    'add_route' => 'divisions.create',
    ])

    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($divisions as $division)
                        <tr>
                            <td>{{ $division->id }}</td>
                            <td>{{ $division->name }}</td>
                            <td>
                                <a href="{{ route('divisions.show', $division) }}" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('divisions.edit', $division) }}" class="btn btn-warning">Редактировать</a>
                                <a href="{{ route('divisions.delete', $division) }}" class="btn btn-danger">Удалить</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
                              

@endsection