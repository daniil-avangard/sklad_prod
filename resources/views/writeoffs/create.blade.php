@extends('layouts.base')

@push('styles-plugins')
    <link href="/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .wrong-value {
            background-color: red !important;
        }
    </style>
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Списание',
        'route' => 'writeoffs.create',
        'breadcrumbs' => 'Добавление списания',
        'back_route' => 'writeoffs',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('writeoffs.store') }}" method="POST" class="form-horizontal well">
                        @csrf

                        <div class="form-group row">

                            <div class="col-sm-4">
                                <label for="writeoff_date" class="form-label">Дата списания</label>
                                <input type="date" name="writeoff_date" class="form-control" onfocus="this.showPicker()" required>
                            </div>

                            <div class="col-sm-4">
                                <label for="reason" class="form-label">Причина списания</label>
                                <input type="text" name="reason" class="form-control" required>
                            </div>

                        </div>

                        <fieldset>
                            <div class="repeater-custom-show-hide">
                                <div data-repeater-list="products">
                                    <div data-repeater-item="">
                                        <div class="form-group row d-flex align-items-end">
                                            <div class="col-sm-4">
                                                <label class="form-label">Товары</label>
                                                <select name="products[0][product_id]"
                                                    class="form-select select2 product-select" required>
                                                    <option value="">Выберите товар</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                                <select id="date_of_actuality_writeoff" value="" class="select-for-actuality-base" style="width: 2px !important; height: 2px !important; visibility: hidden;">
                                                    <option value="">Выберите даты</option>
                                                    @foreach ($products as $product)
                                                        @foreach ($product->variants as $variants)
                                                            @if ($variants->date_of_actuality == "")
                                                            <option value="{{ $variants->product_id }}" data-quantity="{{ $variants->quantity }}">Без даты актуализации</option>
                                                            @else
                                                            <option value="{{ $variants->product_id }}" data-quantity="{{ $variants->quantity }}">{{ date("d.m.Y", strtotime($variants->date_of_actuality)) }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div><!--end col-->

                                            <div class="col-sm-4">
                                                <label class="form-label">Дата актуализации</label>
                                                <select class="form-select actuality-select" name="products[0][date_of_actuality]"
                                                    id="" required>
                                                    <option value="">Выберите товар</option>

                                                </select>
                                            </div>

                                            <div id="wrtf-quontity-check" class="col-sm-3">
                                                <label class="form-label">Количество</label>
                                                <input type="text" name="product[0][quantity]" value=""
                                                    class="form-control writeoff-value" style="margin-bottom: 10px !important;" required>
                                                <label class="form-label">Количество на складе</label>
                                                <input type="text" value="" class="info-for-quantity" style="width: 80px" disabled>
                                            </div><!--end col-->


                                            <div class="col-sm-1">
                                                <span data-repeater-delete="" class="btn btn-outline-danger">
                                                    <span class="far fa-trash-alt me-1"></span> Удалить
                                                </span>
                                            </div><!--end col-->


                                        </div>

                                    </div>
                                </div>
                                <div class="form-group mb-0 row">
                                    <div class="col-sm-12">
                                        <span data-repeater-create="" class="btn btn-outline-secondary">
                                            <span class="fas fa-plus"></span> Добавить
                                        </span>
                                        <input type="submit" value="Создать списание" class="btn btn-outline-primary">
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script src="/plugins/select2/select2.min.js"></script>
    <script src="/plugins/repeater/jquery.repeater.min.js"></script>
    <script src="/assets/pages/jquery.form-repeater.js"></script>
    <script src="/assets/js/newWriteoff.js"></script>
@endpush
