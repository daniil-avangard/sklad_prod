@extends('layouts.base')


@section('content')
@include('includes.breadcrumb', [
    'title' => 'Добавить вариант продукта: ' . $product->name,
    'route' => 'products.variants.create',
    'breadcrumbs' => 'Продукты',
])


@include('products.info.header')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <x-form action="{{ route('products.variants.store', $product) }}" method="POST" enctype="multipart/form-data">
                
                        <div class="col-lg-2 mb-3">
                            <label for="pdf_maket">Макет (PDF)</label>
                            <input type="file" class="form-control" id="pdf_maket" name="pdf_maket" accept="application/pdf">
                        </div>

                        <div class="col-lg-2 mb-3">
                        <div class="checkbox-primary">
                                <input id="is_active" type="checkbox" value="1" checked name="is_active">
                                <label for="is_active">
                                    Доступно к заказу
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="quantity">Количество</label>
                            <input type="number" class="form-control" disabled id="quantity" name="quantity" value="0">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="reserved">Зарезервирован</label>
                            <input type="number" class="form-control" disabled id="reserved" name="reserved" value="0">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="date_of_actuality">Дата актуализации</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="date_of_actuality" name="date_of_actuality">
                                <button type="button" class="btn btn-outline-secondary" id="reset_date" onclick="document.getElementById('date_of_actuality').value = ''">Сбросить</button>
                            </div>
                        </div>

                    <button type="submit" class="btn btn-primary mt-3">Создать</button>
                </x-form>
            </div>
    </div>
</div>


@endsection
