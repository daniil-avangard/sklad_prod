@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Создание группы подразделений</h1>
        <form action="{{ route('groups.divisions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection
