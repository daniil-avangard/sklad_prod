@extends('layouts.base')

@section('content')


<div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Саоздать пользователя</h4>
                                @include('includes.breadcrumb')
                                    </div><!--end col-->
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <x-errors />
            <x-form method="POST" action="{{ route('users.store') }}">
                <div class="form-group">
                    <label for="surname">Фамилия</label>
                    <input type="text" class="form-control" id="surname" name="surname" required>
                </div>
                <div class="form-group">
                    <label for="first_name">Имя</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="midle_name">Отчество</label>
                    <input type="text" class="form-control" id="midle_name" name="midle_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Создать</button>
                </x-form>
            </div>
        </div>
    </div>
</div>

@endsection
