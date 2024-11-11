@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Подразделения',
        'route' => 'divisions',
        'breadcrumbs' => 'Подразделения',
        'add_route' => $canCreateProduct ? 'divisions.create' : null,
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
                                @can('update', \App\Models\Product::class)
                                <a href="{{ route('divisions.edit', $division) }}" class="btn btn-warning">Редактировать</a>
                                @endcan
                                @can('delete', \App\Models\Product::class)
                                    <x-form action="{{ route('divisions.delete', $division) }}" method="DELETE"
                                        style="display: inline-block;">
                                        <button onclick="return confirm('Вы уверены?')" type="submit"
                                            class="btn btn-danger">Удалить</button>
                                    </x-form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
