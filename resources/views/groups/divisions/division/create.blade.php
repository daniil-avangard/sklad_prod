@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Добавление подразделения</h1>
        </div>
    </div>

    <x-form action="{{ route('groups.divisions.division.attach', $group) }}" method="POST">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Название</label>
                    @foreach ($divisions as $division)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $division->id }}"
                                id="division{{ $division->id }}" name="division_id[]">
                            <label class="form-check-label" for="division{{ $division->id }}">
                                {{ $division->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </div>
    </x-form>
@endsection
