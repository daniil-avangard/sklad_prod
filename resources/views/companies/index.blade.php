@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Компании',
        'route' => 'companies',
        'breadcrumbs' => 'Компании',
        'add_route' => 'companies.create',
    ])

    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Адрес</th>
                        <th>Телефон</th>
                        <th>Электронная почта</th>
                        <th>Сайт</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->address }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->site }}</td>
                            <td>
                                <a href="" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning">Редактировать</a>
                                <x-form action="{{ route('companies.delete', $company) }}" method="DELETE"
                                    style="display: inline-block;">
                                    <button type="submit" onclick="return confirm('Вы уверены?')"
                                        class="btn btn-danger">Удалить</button>
                                </x-form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
