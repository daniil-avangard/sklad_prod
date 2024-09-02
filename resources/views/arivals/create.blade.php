@extends('layouts.base')

@push('styles-plugins')
<link href="/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Приход</h4>
                                @include('includes.breadcrumb')
                                    </div><!--end col-->
>  
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->

                            <x-success />

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ route('arivals.store') }}" method="POST" class="form-horizontal well">
                                                @csrf

                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="invoice" class="form-label">Номер документа</label>
                                                        <input type="text" name="invoice" class="form-control">
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <label for="arrival_date" class="form-label">Дата прихода</label>
                                                        <input type="date" name="arrival_date" class="form-control">
                                                    </div>

                                                </div>

                                                <fieldset>
                                                    <div class="repeater-custom-show-hide">
                                                        <div data-repeater-list="products">
                                                            <div data-repeater-item="">
                                                                <div class="form-group row d-flex align-items-end">
                                                                <div class="col-sm-4">
                                                                <label class="form-label">Товары</label>
                                                                <select name="products[0][product_id]" class="form-select select2 product-select">
                                                                    <option value="">Выберите товар</option>
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div><!--end col-->

                                                            <div class="col-sm-4">
                                                                <label class="form-label">Количество</label>
                                                                <input type="text" name="product[0][quantity]" value="0" class="form-control">
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
                                                        <input type="submit" value="Создать приход" class="btn btn-outline-primary">
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
                    if ($option.val() && selectedValues.includes($option.val()) && $option.val() !== $select.val()) {
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
</script>

@endpush