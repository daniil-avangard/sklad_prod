@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Добавление группы подразделений</h1>
        </div>
    </div>

    <x-form action="{{ route('products.groups.divisions.attach', $product) }}" method="POST">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Группа</label>
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
