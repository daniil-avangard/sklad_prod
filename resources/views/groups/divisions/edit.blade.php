@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Изменение группы подразделений</h1>
        <x-form action="{{ route('groups.divisions.update', $group->id) }}" method="PUT">
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $group->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </x-form>
    </div>
@endsection
