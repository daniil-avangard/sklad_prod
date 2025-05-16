@extends('layouts.base')

@section('title_page', 'Редактировать продукт')

@push('styles-plugins')
    <link href="/plugins/dropify/css/dropify.min.css" rel="stylesheet">
@endpush
@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Редактировать продукт ' . $product->name,
        'route' => 'products.edit',
        'breadcrumbs' => $product,
        'back_route' => 'products.show',
        'back_route_param' => $product,
    ])

    @include('products.inc.nav')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-form action="{{ route('products.update', $product) }}" method="PUT" enctype="multipart/form-data">
                        <div class="d-grid gap-4 mb-4" style="grid-template-columns: repeat(2, 1fr);">
                            <div class="card mb-0">
                                <div class="card-header">
                                    <h4 class="card-title">Основная информация</h4>
                                </div>

                                <div class="d-flex flex-column card-body">
                                    <div class="mb-3">
                                        <div class="">
                                            <div class="form-group mb-3">
                                                <label for="sku">Артикул</label>
                                                <input type="text" class="form-control" id="sku" name="sku"
                                                    readonly value="{{ $product->sku }}">
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="form-group mb-3">
                                                <label for="name">Название</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ $product->name }}" required>
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="form-group mb-3">
                                                <label for="company_id">Компания</label>
                                                <select class="form-select" id="company_id" name="company_id">
                                                    <option value="">Выберите компанию</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}"
                                                            {{ $company->id == $product->company_id ? 'selected' : '' }}>
                                                            {{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="form-group">
                                                <label for="category_id">Категория</label>
                                                <select class="form-select" id="category_id" name="category_id">
                                                    <option value="">Выберите категорию</option>
                                                    @foreach ($categories as $category)
                                                        <option
                                                            {{ $category->id == $product->category_id ? 'selected' : '' }}
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="">
                                            <div class="form-group mb-0">
                                                <label for="min_stock">Минимальный остаток</label>
                                                <input type="number" class="form-control" id="min_stock" name="min_stock"
                                                    min="0" placeholder="0" value="{{ $product->min_stock }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <label for="image">Изображение</label>
                                        <input name="image" type="file" id="image" class="dropify-ru"
                                            data-default-file="@if ($product->image) {{ asset('storage/' . $product->image) }} @endif"
                                            data-max-file-size="10M"
                                            data-max-file-size-error="Извините, файл слишком большой"
                                            data-allowed-file-extensions="png jpg jpeg" />
                                        <input type="hidden" name="delete_image" value="0" id="delete_image">
                                    </div>
                                </div>
                            </div>


                            <div class="">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">ККО</h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="checkbox-primary">
                                                    <input id="kko_hall" type="checkbox" value="1" name="kko_hall"
                                                        {{ $product->kko_hall ? 'checked' : '' }}>
                                                    <label for="kko_hall">
                                                        Оперзал
                                                    </label>
                                                </div>

                                                <div class="checkbox-primary">
                                                    <input id="kko_account_opening" type="checkbox" value="0"
                                                        name="kko_account_opening"
                                                        {{ $product->kko_account_opening ? 'checked' : '' }}>
                                                    <label for="kko_account_opening">
                                                        Открытие счетов
                                                    </label>
                                                </div>

                                                <div class="checkbox-primary">
                                                    <input id="kko_manager" type="checkbox" value="1"
                                                        name="kko_manager" {{ $product->kko_manager ? 'checked' : '' }}>
                                                    <label for="kko_manager">
                                                        Менеджеру
                                                    </label>
                                                </div>

                                            </div>

                                            <div class="form-group mb-0">
                                                <label for="kko_operator">Операционист</label>
                                                <select id="kko_operator" name="kko_operator" class="form-select">
                                                    @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                        <option value="{{ $operator->value }}"
                                                            {{ $product->kko_operator === $operator ? 'selected' : '' }}>
                                                            {{ $operator->name() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Экспресс</h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group mb-3">
                                                <div class="checkbox-primary">
                                                    <input id="express_hall" type="checkbox" value="1"
                                                        name="express_hall" {{ $product->express_hall ? 'checked' : '' }}>
                                                    <label for="express_hall">
                                                        Оперзал
                                                    </label>
                                                </div>

                                            </div>

                                            <div class="form-group mb-0">
                                                <label for="kko_operator">Операционист</label>
                                                <select id="express_operator" name="express_operator"
                                                    class="form-select">
                                                    @foreach (App\Enum\Products\PointsSale\Operator::cases() as $operator)
                                                        <option value="{{ $operator->value }}"
                                                            {{ $product->express_operator === $operator ? 'selected' : '' }}>
                                                            {{ $operator->name() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label for="description">Описание</label>
                                    <textarea class="form-control" id="description" name="description" rows="10">{{ $product->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" name="action" value="save">
                                Сохранить
                            </button>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/plugins/dropify/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            var drEvent = $('.dropify-ru').dropify({
                messages: {
                    default: 'Перетащите файл сюда или нажмите',
                    replace: 'Перетащите или нажмите, чтобы заменить',
                    remove: 'Удалить',
                    error: 'Ой, что-то пошло не так.'
                }
            });

            drEvent.on('dropify.beforeClear', function(event, element) {
                $('#delete_image').val('1');
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                $('#delete_image').val('1');
            });
        });
    </script>
@endpush
