@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Списания',
    'route' => 'writeoffs',
    'breadcrumbs' => 'Списания',
    'add_route' => $canCreateWriteoff ? 'writeoffs.create' : null
])

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Дата списания</th>
                    <th>Пользователь</th>
                    <th>Статус</th>
                    <th>Дата создания</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($writeoffs as $writeoff)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($writeoff->writeoff_date)->format('d.m.Y') }}</td>
                        <td>{{ $writeoff->user->surname }} {{ $writeoff->user->first_name }} {{ $writeoff->user->middle_name }}</td>
                    <td>
                        <span class="badge bg-{{ $writeoff->status->color() }}">
                            {{ $writeoff->status->name() }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($writeoff->created_at)->format('d.m.Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('writeoffs.show', $writeoff->id) }}" class="btn btn-primary">Посмотреть</a>
                        @if($writeoff->status === \App\Enum\WriteoffStatusEnum::pending)
                            @can('changeStatus', $writeoff)
                                <a href="{{ route('writeoffs.accepted', $writeoff->id) }}" class="btn btn-success">Принять</a>
                                <a href="{{ route('writeoffs.rejected', $writeoff->id) }}" class="btn btn-danger">Отклонить</a>
                            @endcan
                        @endif
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>




@endsection
