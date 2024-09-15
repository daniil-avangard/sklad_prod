@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Подразделения',
        'route' => 'divisions.edit',
        'breadcrumbs' => 'Редактирование подразделения',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-form action="{{ route('divisions.update', $division) }}" method="PUT" class="form-horizontal well">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" name="name" value="{{ $division->name }}" class="form-control"
                                    required>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
@endsection
