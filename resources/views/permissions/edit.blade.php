@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Редактирование полномочия', 
    'route' => 'permissions.edit', 
    'breadcrumbs' => $permission->name,
    'param' => $permission
])

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
