@extends('layouts.base')

@section('content')

@include('includes.breadcrumb', [
    'title' => 'Приходы',
    'route' => 'arivals',
    'breadcrumbs' => 'Приходы',
    'add_route' => $canCreateArival ? 'arivals.create' : null
])

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата поставки</th>
                    <th>Пользователь</th>
                    <th>Статус</th>
                    <th>Дата создания</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($arivals as $arival)
                <tr>
                    <td>{{ $arival->invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($arival->arrival_date)->format('d.m.Y') }}</td>
                        <td>{{ $arival->user->surname }} {{ $arival->user->first_name }} {{ $arival->user->middle_name }}</td>
                    <td>
                        <span class="badge bg-{{ $arival->status->color() }}">
                            {{ $arival->status->name() }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($arival->created_at)->format('d.m.Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('arivals.show', $arival->id) }}" class="btn btn-primary">Посмотреть</a>
                        @if($arival->status === \App\Enum\ArivalStatusEnum::pending)
                            @can('changeStatus', $arival)
                                <a href="{{ route('arivals.accepted', $arival->id) }}" class="btn btn-success">Принять</a>
                                <a href="{{ route('arivals.rejected', $arival->id) }}" class="btn btn-danger">Отклонить</a>
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
