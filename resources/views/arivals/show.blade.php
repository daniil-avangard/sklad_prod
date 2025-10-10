@extends('layouts.base')

@push('styles-plugins')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                                <button type="button" class="btn btn-success" data-action="accept" data-arival-id="{{ $arival->id }}">Принять</button>
                                <button type="button" class="btn btn-danger" data-action="reject" data-arival-id="{{ $arival->id }}">Отклонить</button>
                                <button type="button" class="btn btn-success" data-action="accept-with-changes" data-arival-id="{{ $arival->id }}">Принять с изменениями</button>
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
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($arivalProducts as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    @if (is_null($item->date_of_actuality))
                                        <p class="m-0">Без даты</p>
                                    @else
                                        <p class="m-0">
                                            {{ \Carbon\Carbon::parse($item->date_of_actuality)->format('d.m.Y') }}
                                        </p>
                                    @endif
                                    
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $item->product_id }}" id="accept_{{ $item->product_id }}" value="accept" data-productid="{{ $item->product_id }}">
                                        <label class="form-check-label" for="accept_{{ $item->product_id }}">Принять</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $item->product_id }}" id="reject_{{ $item->product_id }}" value="reject" data-productid="{{ $item->product_id }}">
                                        <label class="form-check-label" for="reject_{{ $item->product_id }}">Отклонить</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status_{{ $item->product_id }}" id="pending_{{ $item->product_id }}" value="pending" data-productid="{{ $item->product_id }}">
                                        <label class="form-check-label" for="pending_{{ $item->product_id }}">Ожидание</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/arival-actions.js') }}"></script>
@endpush

@endsection