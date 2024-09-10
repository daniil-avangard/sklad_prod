@extends('layouts.base')

@section('content')
<div class="row">
<div class="col-sm-12">
    <div class="page-title-box">
        <div class="row">
            <div class="col">
                <h4 class="page-title">{{ $product->name }}</h4>
{{ Breadcrumbs::render('products.variants.edit', $product, $variant) }}
</div><!--end col-->
            
            <div class="col-auto align-self-center">
                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">Назад</a>
            </div><!--end col-->  

</div><!--end row-->                                                              
</div><!--end page-title-box-->
</div><!--end col-->
</div><!--end row-->
<!-- end page title end breadcrumb -->
<x-success />
<x-errors />

<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0">
        <li class="nav-item">
        <a class="nav-link active">Просмотр</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{ route('products.edit', $product->id) }}">Редактировать</a>
        </li>
    </ul>        
</div>
@include('products.info.header')

<x-form action="{{ route('products.variants.update', ['product' => $product, 'variant' => $variant]) }}" method="PUT">
    <div class="row">
        <div class="col-lg-1 mb-1">
            <label for="sku">Артикул</label>
            <input type="text" class="form-control" disabled id="sku" value="{{ $variant->sku }}">
                </div>
                        <div class="col-lg-2 mb-2">
                            <label for="pdf_maket">Макет (PDF)</label>
                            <input type="file" class="form-control" id="pdf_maket" name="pdf_maket" accept="application/pdf">
                            @if($variant->image)
                                <a href="{{ asset('storage/'.$variant->image) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2">Просмотреть текущий макет</a>
                            @endif
                        </div>

                        <div class="col-lg-2 mb-3 d-flex align-items-center justify-content-center">
                            <div class="checkbox-primary">
                                <input id="is_active" 
                                type="checkbox" value="1" 
                                {{ $variant->is_active ? 'checked' : '' }} name="is_active" 
                                style="width: 15px; height: 15px;">
                                <label for="is_active" style="font-size: 14px; margin-left: 5px;">
                                    Доступно к заказу
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="quantity">Количество</label>
                            <input type="number" class="form-control" disabled id="quantity" name="quantity" value="{{ $variant->quantity }}">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="reserved">Зарезервирован</label>
                            <input type="number" class="form-control" id="reserved" name="reserved" value="{{ $variant->reserved }}">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label for="date_of_actuality">Дата актуализации</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="date_of_actuality" name="date_of_actuality" value="{{ $variant->date_of_actuality }}">
                                <button type="button" class="btn btn-outline-secondary" id="reset_date" onclick="document.getElementById('date_of_actuality').value = ''">Сбросить</button>
                            </div>
                        </div>
                        <div class="col-lg-1 mt-3">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>

</x-form>
@endsection