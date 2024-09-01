@extends('layouts.base')

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
                                                <fieldset>
                                                    <div class="repeater-default">
                                                        <div data-repeater-list="product">
                                                            <div data-repeater-item="">
                                                                <div class="form-group row d-flex align-items-end">
                                                                <div class="col-sm-4">
                                                                <label class="form-label">Make</label>
                                                                <select name="product[0][make]" class="form-select">
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
                                                                    <span class="far fa-trash-alt me-1"></span> Delete
                                                                </span>
                                                            </div><!--end col-->


                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-0 row">
                                                    <div class="col-sm-12">
                                                        <span data-repeater-create="" class="btn btn-outline-secondary">
                                                            <span class="fas fa-plus"></span> Add
                                                        </span>
                                                        <input type="submit" value="Submit" class="btn btn-outline-primary">
                                                    </div><!--end col-->
                                                </div><!--end row--> 
                                                    </div>
                                                </fieldset>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    
@endsection

@push('scripts-plugins')
<script src="/plugins/repeater/jquery.repeater.min.js"></script>
<script src="/assets/pages/jquery.form-repeater.js"></script>
@endpush