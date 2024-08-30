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
            <x-form method="PUT" action="{{ route('permissions.update', $permission->id) }}">
                
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="guard_name">Событие</label>
                    <input type="text" class="form-control" id="guard_name" name="guard_name" disabled value="{{ $permission->getName() }}" required>
                </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </x-form>
            </div>
        </div>
    </div>
</div>

@endsection
