@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Подразделения',
        'route' => 'divisions.create',
        'breadcrumbs' => 'Подразделения',
        'back_route' => 'divisions',
    ])

    {{-- <div>
        <div class="" id="products-info-division" style="display: block;">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Категория</h4>
                        </div>

                        <div class="d-flex col-auto">
                            <div class="form-group mb-0 me-2">
                                <input type="text" name="city" id="city" class="form-control"
                                    placeholder="Название категории" required>
                            </div>

                            <button class="btn btn-primary" id="add-division-category">
                                Добавить
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2" id="list-divisions"
                            data-product-id="10002">
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="1">
                                Полянка
                            </li>
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="4">
                                Болянка
                            </li>
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="5">
                                Ленина
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="" id="products-info-division" style="display: block;">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Город</h4>
                        </div>

                        <div class="d-flex col-auto">
                            <div class="form-group mb-0 me-2">
                                <input type="text" name="city" id="city" class="form-control"
                                    placeholder="Название города" required>
                            </div>

                            <button class="btn btn-primary" id="add-division-city">
                                Добавить
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2" id="list-divisions"
                            data-product-id="10002">
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="1">
                                Полянка
                            </li>
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="4">
                                Болянка
                            </li>
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border
            border-dark-subtle"
                                data-division-id="5">
                                Ленина
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="" id="products-info-division" style="display: block;">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Город</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <div class="form-group">
                            <label for="name">Отдел</label>
                            <input type="text" name="department" id="department" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    <div class="row justify-content-between">
        <div class="row col-8">
            <div class="card mb-0">
                <div class="card-header">
                    <h4 class="card-title">
                        Добавить подразделение
                    </h4>
                </div>

                <div class="card-body">
                    <x-form class="" action="#" method="POST" id="add-division-form">
                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select class="form-select" id="category_id" name="category_id">
                                @foreach ($divisionCategory as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Город</label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Отдел</label>
                            <input type="text" name="department" id="department" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Создать</button>
                    </x-form>
                </div>
            </div>
        </div>

        <div class="card col-4 mb-0">
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
                    <h5 class="text-muted mb-2">
                        Список категорий
                    </h5>

                    <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2">
                        @foreach ($divisionCategory as $category)
                            <li class="division__item p-2 ps-4 pe-4 rounded text-center border border-dark-subtle">
                                {{ $category->category_name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/assets/js/createDivision.js"></script>

    <!-- Добавляет категорию -->
    <script src="/assets/js/createDivisionCategory.js"></script>
@endpush
