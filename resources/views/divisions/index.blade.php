@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Подразделения',
        'route' => 'divisions',
        'breadcrumbs' => 'Подразделения',
        'add_route' => $canCreateProduct ? 'divisions.create' : null,
    ])

    {{-- @include('divisions.inc.nav') --}}

    <div class="d-flex justify-content-between">
        <div class="col-9 row" id="division-list" {{-- style="display: none" --}}>
            <div class="col-12">
                {{-- <table class="table table-striped"> --}}
                <table class="table table-bordered">
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
                                <td>Просто Тест</td>
                                <td>какой-то город</td>
                                <td>{{ $division->name }}</td>
                                <td class="">
                                    <a href="{{ route('divisions.show', $division) }}" class="btn btn-primary button-icon-wrapper">
                                        <i data-feather="eye" class="align-self-center topbar-icon button-icon"></i>
                                    </a>
                                    @can('update', \App\Models\Product::class)
                                        <a href="{{ route('divisions.edit', $division) }}" class="btn btn-warning button-icon-wrapper">
                                            <i data-feather="edit" class="align-self-center topbar-icon button-icon"></i>
                                        </a>
                                    @endcan
                                    @can('delete', \App\Models\Product::class)
                                        <x-form action="{{ route('divisions.delete', $division) }}" method="DELETE"
                                            style="display: inline-block;">
                                            <button onclick="return confirm('Вы уверены?')" type="submit"
                                                class="btn btn-danger button-icon-wrapper">
                                                <i data-feather="trash" class="align-self-center topbar-icon button-icon"></i>
                                            </button>
                                        </x-form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-3 mb-4" id="division-category" {{-- style="display: none" --}}>
            {{-- <button class="btn btn-primary">Добавить категорию</button> --}}

            {{-- <div class="d-flex">
                <p>
                    Добавить категорию
                </p>

                <div class="form-group mb-3">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" id="name" name="name" value=""
                        placeholder="Название категории" required="">
                </div>

                <button class="btn btn-primary">Добавить категорию</button>
            </div> --}}

            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Фильтрация</h4>

                        {{-- <i data-feather="plus"
                            class="align-self-center topbar-icon button-icon button-icon--big text-primary"></i> --}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <p class="mb-2">
                            Категория
                        </p>

                        <ul class="m-0 p-0 list-unstyled d-flex flex-column gap-2" id="list-divisions">
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
                                border-dark-subtle"
                                data-division-id="1">
                                Владивосток
                            </li>
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
                                border-dark-subtle"
                                data-division-id="1">
                                Владивосток
                            </li>
                        </ul>
                    </div>

                    <div style="max-height: 300px; overflow-y: auto;">
                        <p class="mb-2">
                            Город
                        </p>

                        <div class="form-group mb-0">
                            {{-- <label for="company_id">Компания</label> --}}
                            <select class="form-select" id="company_id" name="company_id">
                                <option value="">Выберите компанию</option>
                                <option value="2" selected="">
                                    Авангард Агро</option>
                                <option value="1">
                                    Банк Авангард</option>
                            </select>
                        </div>
                    </div>
                </div><!--end card-body-->
            </div>
        </div>

    </div>
@endsection
