@extends('layouts.base')

@section('content')

<div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Дабвить продукт</h4>
                                            @include('includes.breadcrumb')
                                    </div><!--end col-->

                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Добавить продукт</h4>

                                        <x-form action="{{ route('products.store') }}" method="POST">
                                        <div class="form-group">
                                            <label for="name">Название</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Описание</label>
                                            <textarea class="form-control" id="description" name="description" required>qwe</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Добавить</button>
                                        </x-form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title end breadcrumb -->

                </div><!-- container -->

@endsection

@push('scripts-plugins')
        <!--Wysiwig js-->
        <script src="/plugins/tinymce/tinymce.min.js"></script>
        <script src="/assets/pages/jquery.form-editor.init.js"></script>
@endpush