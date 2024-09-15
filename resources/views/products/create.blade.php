@extends('layouts.base')

@push('styles-plugins')
    <link href="/plugins/dropify/css/dropify.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Добавить продукт',
        'route' => 'products.create',
        'breadcrumbs' => 'Продукты',
        'back_route' => 'products',
    ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-8 row">
                                            <div class="col-lg-6">
                                                <div class="form-group mb-4">
                                                    <label for="name">Название</label>
                                                    <input type="text" class="form-control" value="{{ old('name') }}"
                                                        id="name" name="name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group mb-3">
                                                    <label for="sku">Артикул</label>
                                                    <input type="text" class="form-control" value="{{ old('sku') }}"
                                                        id="sku" name="sku">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                    <label for="company_id">Компания</label>
                                                    <select class="form-select" id="company_id" name="company_id">
                                                        <option value="">Выберите компанию</option>
                                                        @foreach ($companies as $company)
                                                            <option
                                                                {{ old('company_id') == $company->id ? 'selected' : '' }}
                                                                value="{{ $company->id }}">{{ $company->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="category_id">Категория</label>
                                                <select class="form-select" id="category_id" name="category_id">
                                                    <option value="">Выберите категорию</option>
                                                    @foreach ($categories as $category)
                                                        <option {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="image">Изображение</label>
                                            <input name="image" type="file" id="image" class="dropify-ru"
                                                data-max-file-size="10M"
                                                data-max-file-size-error="Извините, файл слишком большой" />
                                        </div>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->

                        <div class=row>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Необходимость размещения в точках продаж</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">


                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title">ККО</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group mb-3 col-lg-6">
                                                                <div class="checkbox-primary">
                                                                    <input id="kko_hall" type="checkbox" value="1"
                                                                        name="kko_hall"
                                                                        {{ old('kko_hall') ? 'checked' : '' }}>
                                                                    <label for="kko_hall">
                                                                        Оперзал
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox-primary">
                                                                    <input id="kko_account_opening" type="checkbox"
                                                                        value="1" name="kko_account_opening"
                                                                        {{ old('kko_account_opening') ? 'checked' : '' }}>
                                                                    <label for="kko_account_opening">
                                                                        Открытие счетов
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox-primary">
                                                                    <input id="kko_manager" type="checkbox" value="1"
                                                                        name="kko_manager"
                                                                        {{ old('kko_manager') ? 'checked' : '' }}>
                                                                    <label for="kko_manager">
                                                                        Менеджеру
                                                                    </label>
                                                                </div>

                                                            </div>

                                                            <div class="form-group mb-3 col-lg-6">
                                                                <label for="kko_operator">Оперциансту</label>
                                                                <select id="kko_operator" name="kko_operator"
                                                                    class="form-select">
                                                                    @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                                        <option
                                                                            {{ old('kko_operator') == $operator->value ? 'selected' : '' }}
                                                                            value="{{ $operator->value }}">
                                                                            {{ $operator->name() }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>




                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title">Экспресс</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="form-group mb-3 col-lg-6">
                                                                <div class="checkbox-primary">
                                                                    <input id="express_hall" type="checkbox" value="1"
                                                                        name="express_hall"
                                                                        {{ old('express_hall') ? 'checked' : '' }}>
                                                                    <label for="express_hall">
                                                                        Оперзал
                                                                    </label>
                                                                </div>

                                                            </div>

                                                            <div class="form-group mb-3 col-lg-6">
                                                                <label for="kko_operator">Оперциансту</label>
                                                                <select id="express_operator" name="express_operator"
                                                                    class="form-select">
                                                                    @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                                        <option
                                                                            {{ old('express_operator') == $operator->value ? 'selected' : '' }}
                                                                            value="{{ $operator->value }}">
                                                                            {{ $operator->name() }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <div class="form-group mb-3 col-lg-12">
                                    <label for="description">Описание</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary" name="action"
                                        value="save">Добавить</button>

                                </div>
                            </div>


                    </x-form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/plugins/dropify/js/dropify.min.js"></script>
    <script src="/assets/pages/jquery.form-upload.init.js"></script>
@endpush
