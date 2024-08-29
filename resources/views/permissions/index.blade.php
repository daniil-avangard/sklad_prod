@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Полномочия</h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <div class="card-body">
        @if($permissions->isEmpty())
            Нет ни одной записи.
        @else
            <div class="table-responsive">
                <table class="table table-borderless text-nowrap mb-0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>
                                {{ $permission->id }}
                            </td>
                            <td>
                                {{ $permission->name }}
                            </td>

                            <td>
                                <a href="#">
                                    {{ $permission->getName() }}
                                </a>
                            </td>

                            <td>
                                <form action="" method="POST">
                                    @csrf

                                    <input type="hidden" name="permission_id" value="{{ $permission->id }}">

                                    <a href="#" onclick="event.preventDefault(); this.parentElement.submit();" class="text-danger small">
                                        Удалить
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
            </div>
        </div>
    </div>
</div>
@endsection
