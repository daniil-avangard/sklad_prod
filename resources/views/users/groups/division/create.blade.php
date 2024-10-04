@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Добавление группы подразделения</h1>
        </div>
    </div>

    <x-form action="{{ route('user.groups.division.attach', $user) }}" method="POST">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Название</label>
                    @foreach ($groups as $group)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $group->id }}"
                                id="group{{ $group->id }}" name="group_id[]">
                            <label class="form-check-label" for="group{{ $group->id }}">
                                {{ $group->name }}
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
