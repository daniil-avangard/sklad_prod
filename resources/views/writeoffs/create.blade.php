@extends('layouts.base')

@push('styles-plugins')
    <link href="/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
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
                                                            <option value="{{ $variants->product_id }}" data-quantity="{{ $variants->quantity }}">{{ $variants->date_of_actuality }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div><!--end col-->

                                            <div class="col-sm-4">
                                                <label class="form-label">Дата актуализации</label>
                                                <select class="form-select" name="products[0][date_of_actuality]"
                                                    id="" required>
                                                    <option value="">Выберите товар</option>

                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <label class="form-label">Количество</label>
                                                <input type="text" name="product[0][quantity]" value=""
                                                    class="form-control" required>
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


    <script>
        $(document).ready(function() {
            function updateSelectOptions() {
                var selectedValues = [];
                $('.product-select').each(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue) {
                        selectedValues.push(selectedValue);
                    }
                });

                $('.product-select').each(function() {
                    var $select = $(this);
                    $select.find('option').each(function() {
                        var $option = $(this);
                        if ($option.val() && selectedValues.includes($option.val()) && $option
                            .val() !== $select.val()) {
                            $option.prop('disabled', true);
                        } else {
                            $option.prop('disabled', false);
                        }
                    });
                });
            }

            $('.select2').select2();

            $(document).on('change', '.product-select', function() {
                updateSelectOptions();
            });

            $(document).on('click', '[data-repeater-create]', function() {
                setTimeout(function() {
                    $('.select2').select2();
                    updateSelectOptions();
                }, 100);
            });

            updateSelectOptions();
        });

        $(document).on('change', '.product-select', function() {
            var $select = $(this);
            var dateSelect = $select.closest('.form-group').find('select[name$="[date_of_actuality]"]');
            dateSelect.find('option').remove().end();

            var productId = $select.val();
            console.log(productId);
            if (productId && productId !== '') {
                $.ajax({
                    url: '{{ route('writeoffs.variants.dates') }}',
                    type: 'GET',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        console.log(response);
                        dateSelect.append('<option value="">Выберите даты</option>');
                        $.each(response, function(key, value) {
                            
                            if (value['date'] == null) {
                                dateSelect.append('<option value="">Без даты</option>');
                            } else {
                                dateSelect.append('<option value="' + moment(value['date'])
                                    .format('DD.MM.YYYY') + '">' +
                                    moment(value['date']).format('DD.MM.YYYY') +
                                    '</option>');
                            }

                        });
                    }
                });
            }
        });
    </script>
@endpush
