@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Категории',
        'route' => 'categories',
        'breadcrumbs' => 'Категории',
        'add_route' => $canCreateProduct ? 'categories.create' : null
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
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                {{-- Не работает --}}
                                {{-- <a href="{{ route('categories.show', $category) }}" class="btn btn-primary">Просмотр</a> --}}
                                <a href="#" class="btn btn-primary">Просмотр</a>
                                @can('update', \App\Models\Product::class)
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Редактировать</a>
                                @endcan
                                @can('delete', \App\Models\Product::class)
                                    <x-form action="{{ route('categories.delete', $category) }}" method="DELETE"
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
@endSection
