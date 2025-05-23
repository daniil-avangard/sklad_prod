@extends('layouts.base')

@section('content')


@include('includes.breadcrumb', [
    'title' => 'Приход ' . $arival->invoice, 
    'route' => 'arivals.show', 
    'breadcrumbs' => 'Приходы',
    'param' => $arival,
])


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="card-text">Номер: {{ $arival->invoice }}</p>
                <p class="card-text">Дата поставки: {{ \Carbon\Carbon::parse($arival->arrival_date)->format('d.m.Y') }}</p>
                <p class="card-text">Пользователь: {{ $arival->user->surname }} {{ $arival->user->first_name }} {{ $arival->user->middle_name }}</p>
                <p class="card-text">Статус:
                    <span class="badge bg-{{ $arival->status->color() }}">
                        {{ $arival->status->name() }}
                    </span>
                    @if($arival->status === \App\Enum\ArivalStatusEnum::pending)
                            @can('changeStatus', $arival)
                                <a href="{{ route('arivals.accepted', $arival->id) }}" class="btn btn-success">Принять</a>
                                <a href="{{ route('arivals.rejected', $arival->id) }}" class="btn btn-danger">Отклонить</a>
                    @endcan
                @endif
                </p>

                <p class="card-text">Дата создания: {{ \Carbon\Carbon::parse($arival->created_at)->format('d.m.Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Товары</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Дата актуализации</th>
                            <th>Количество</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($arivalProducts as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date_of_actuality)->format('d.m.Y') }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>






@endsection 