@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Создание подразделения</h1>
            </div>
        </div>
    </div>

    <x-form action="{{ route('divisions.store') }}" method="POST">
        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </x-form>
@endsection
