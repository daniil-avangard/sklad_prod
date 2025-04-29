@extends('layouts.base')

@push('styles-plugins')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Просмотр подразделения',
        'route' => 'divisions.show',
        'breadcrumbs' => 'Подразделения',
        'back_route' => 'divisions',
    ])

    <div class="d-flex mb-4 gap-4">
        <div class="col p-0">
            <div class="card mb-0">
                <div class="card-header">
                    <h4 class="card-title">Название: {{ $division->name }}</h4>
                </div>

                <div class="card-body">
                    <x-form class="" action="{{ route('divisions.update', $division) }}" method="POST">
                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="0">Выберите категорию</option>
                                @foreach ($divisionCategory as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $category->id == $divisionCategoryId ? 'selected' : '' }}>
                                        {{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Город</label>
                            <input type="text" name="city" id="city" class="form-control"
                                value="{{ $division->city }}" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Отдел</label>
                            <input type="text" name="department" id="department" class="form-control"
                                value="{{ $division->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Сортировка для Excel</label>
                            <input type="number" name="sort_for_excel" id="sort_for_excel" class="form-control"
                                value={{ $division->sort_for_excel }} required>
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </x-form>
                </div>
            </div>
        </div>

        <div class="card col-4 p-0 mb-0">
            <div class="card-header">
                <h4 class="card-title">
                    Категории подразделений
                </h4>
            </div>

            <div class="card-body">
                <div>
                    <h5 class="text-muted mb-2">
                        Добавить категорию
                    </h5>

                    <x-form class="flex-grow-0 mb-4" action="#" method="POST" id="add-category-division">
                        <div class="d-flex">
                            <input type="text" class="form-control me-2" id="division_category" name="division_category"
                                value="" placeholder="Название категории" required="">

                            <button class="btn btn-primary">Добавить</button>
                        </div>
                    </x-form>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-muted m-0">
                            Список категорий
                        </h5>

                        <button class="btn btn-danger button-icon-wrapper" id="delete-category-button">
                            <i data-feather="trash" class="align-self-center topbar-icon button-icon"></i>
                        </button>
                    </div>

                    <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2" id="division-category-list">
                        @foreach ($divisionCategory as $category)
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border border-dark-subtle"
                                data-division-id="{{ $category->id }}">
                                {{ $category->category_name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @can('create', \App\Models\Product::class)
        <div class="row">
            <div class="col-12">
                @include('divisions.inc.list_product', ['products' => $products])
            </div>
        </div>
    @endcan
@endsection

@push('scripts-plugins')
    <script src="/assets/js/createDivision.js"></script>
@endpush
