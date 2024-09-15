@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Компании',
        'route' => 'companies',
        'breadcrumbs' => 'Добавление компании',
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-form action="{{ route('companies.store') }}" method="POST" class="form-horizontal well">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-sm-4">
                                <label for="address" class="form-label">Адрес</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                            </div>

                            <div class="col-sm-4">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                            </div>

                            <div class="col-sm-4">
                                <label for="email" class="form-label">Электронная почта</label>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                            </div>

                            <div class="col-sm-4">
                                <label for="site" class="form-label">Сайт</label>
                                <input type="text" name="site" value="{{ old('site') }}" class="form-control">
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
