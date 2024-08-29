@extends('layouts.base')

@section('content')

 <!-- Page-Title -->
 <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Редактирование пользователя</h4>
                                @include('includes.breadcrumb')
                                    </div><!--end col-->

                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <x-errors />
            <x-form method="PUT" action="{{ route('users.update', $user->id) }}">
                <div class="form-group">
                    <label for="surname">Фамилия</label>
                    <input type="text" class="form-control" id="surname" name="surname" value="{{ $user->surname }}" required>
                </div>
                <div class="form-group">
                    <label for="first_name">Имя</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Отчество</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $user->middle_name }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </x-form>
            </div>
        </div>
    </div>
</div>

@endsection
