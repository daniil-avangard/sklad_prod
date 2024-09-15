@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Категории',
        'route' => 'categories',
        'breadcrumbs' => 'Категории',
        'add_route' => 'categories.create',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-form action="{{ route('categories.store') }}" method="POST" class="form-horizontal well">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-sm-4">
                                <label for="description" class="form-label">Описание</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
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
@endSection
