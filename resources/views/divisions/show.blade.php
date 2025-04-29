@extends('layouts.base')

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
                    <div class="form-group">
                        <label for="category_id">Категория</label>
                        <select class="form-select" id="category_id" name="category_id" disabled>
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
                            value="{{ $division->city }}" disabled required>
                    </div>

                    <div class="form-group">
                        <label for="name">Отдел</label>
                        <input type="text" name="department" id="department" class="form-control"
                            value="{{ $division->name }}" disabled required>
                    </div>

                    <div class="form-group mb-0">
                        <label for="name">Сортировка для Excel</label>
                        <input type="number" name="sort_for_excel" id="sort_for_excel" class="form-control"
                            value={{ $division->sort_for_excel }} disabled required>
                    </div>
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
