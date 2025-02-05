@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Группы подразделений',
        'route' => 'groups.divisions',
        'breadcrumbs' => 'Группы подразделений',
        'add_route' => $canCreateProduct ? 'groups.divisions.create' : null,
    ])

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $group)
                <tr>
                    <td>
                        <a href="{{ route('groups.divisions.show', $group) }}">
                            {{ $group->name }}</a>
                    </td>
                    <td>{{ $group->description }}</td>
                    <td>
                        @can('update', \App\Models\Product::class)
                            <a href="{{ route('groups.divisions.edit', $group->id) }}" class="btn btn-warning">Изменить</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
