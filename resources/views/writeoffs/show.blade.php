@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Списание ' . $writeoff->invoice,
        'route' => 'writeoffs.show',
        'breadcrumbs' => 'Списания',
        'param' => $writeoff,
    ])


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">Дата списания:
                        {{ \Carbon\Carbon::parse($writeoff->writeoff_date)->format('d.m.Y') }}</p>
                    <p class="card-text">Создал: {{ $writeoff->user->surname }} {{ $writeoff->user->first_name }}
                        {{ $writeoff->user->middle_name }}</p>
                    <p class="card-text">Причина: {{ $writeoff->reason }}</p>
                    <p class="card-text">Статус:
                        <span class="badge bg-{{ $writeoff->status->color() }}">
                            {{ $writeoff->status->name() }}
                        </span>
                        @if ($writeoff->status === \App\Enum\WriteoffStatusEnum::pending)
                            @can('changeStatus', $writeoff)
                                <a href="{{ route('writeoffs.accepted', $writeoff->id) }}" class="btn btn-success">Принять</a>
                                <a href="{{ route('writeoffs.rejected', $writeoff->id) }}" class="btn btn-danger">Отклонить</a>
                            @endcan
                        @endif
                    </p>
                    <p class="card-text">Дата создания: {{ $writeoff->created_at }}</p>
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
                            @foreach ($writeoffProducts as $item)
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
