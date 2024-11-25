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
        {{-- <div class="col-3 max-h-40 mb-4" id="division-category" --}}
        {{-- style="display: none" --}}
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


            {{-- <div class="form-group mb-3">
                            <label for="name">Название</label>
                            <input type="text" class="form-control" id="name" name="name" value=""
                                placeholder="Название категории" required="">

                                <button class="btn btn-primary">Добавить категорию</button>

                        </div> --}}

            {{-- <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Фильтрация</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <p class="mb-0">
                                Категория
                            </p>

                            <i data-feather="plus" class="align-self-center topbar-icon button-icon"></i>
                        </div>

                        <div class="form-group col-lg-6 mb-0">
                            <div class="checkbox-primary">
                                <input id="kko_hall" type="checkbox" value="1" name="kko_hall" checked="">
                                <label for="kko_hall">
                                    Оперзал
                                </label>
                            </div>

                            <div class="checkbox-primary">
                                <input id="kko_account_opening" type="checkbox" value="0" name="kko_account_opening"
                                    checked="">
                                <label for="kko_account_opening">
                                    Открытие счетов
                                </label>
                            </div>

                            <div class="checkbox-primary">
                                <input id="kko_manager" type="checkbox" value="1" name="kko_manager" checked="">
                                <label for="kko_manager">
                                    Менеджеру
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="max-height: 300px; overflow-y: auto;">
                        <p class="mb-2">
                            Город
                        </p>

                        <div class="form-group mb-0">
                            <input type="text" class="form-control" value="" id="city" name="city"
                                required="">
                        </div>
                    </div>
                </div>
            </div> --}}
        {{-- </div> --}}

        <div class="col-12 row" id="division-list" {{-- style="display: none" --}}>
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
                            <td>{{ $division->city }}</td>
                            <td>{{ $division->name }}</td>
                            <td class="">
                                <a href="{{ route('divisions.show', $division) }}"
                                    class="btn btn-primary button-icon-wrapper">
                                    <i data-feather="eye" class="align-self-center topbar-icon button-icon"></i>
                                </a>
                                @can('update', \App\Models\Product::class)
                                    <a href="{{ route('divisions.edit', $division) }}"
                                        class="btn btn-warning button-icon-wrapper">
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
@endsection
