@extends('layouts.base')

@section('content')
<div class="container">
    <h1>Инвентаризация</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Продукт</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                <tr>
                    <td>{{ $inventory['product'] }}</td>
                    <td>{{ $inventory['quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('inventories.create') }}" class="btn btn-primary">Добавить продукт</a>
</div>
@endsection

