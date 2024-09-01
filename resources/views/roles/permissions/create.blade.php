@extends('layouts.base')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h1>Добавление прав доступа</h1>
            </div>
        </div>
    </div>
</div>

<form action="" method="POST">
                @csrf

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Полномочие *
                            </label>

                            <select name="permission_id" class="form-control">
                                <option>Выбрать</option>

                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}">
                                        {{ $permission->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <button type="submit" class="btn btn-primary w-100">
                            Сохранить
                        </button>
                    </div>

                    <div class="col-12 col-md-6">
                        <a href="" class="btn btn-light w-100">
                            Отменить
                        </a>
                    </div>
                </div>
            </form>
@endsection

