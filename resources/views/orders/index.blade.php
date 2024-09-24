@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказы',
        'route' => 'orders',
        'breadcrumbs' => 'Заказы',
        // 'add_route' => 'orders.create',
    ])


    <div class="row">
        <div class="col-12">

            <div class="table-responsive">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Выбрать</th>
                            <th>ID</th>
                            <th>Подразделение</th>
                            <th>Статус</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}">
                                        Заказ № {{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->division->name }}</td>
                                <td><span class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    {{-- <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-primary">Просмотр</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button id="view-selected" class="btn btn-success">Просмотреть выбранные заказы</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script>
        document.getElementById('view-selected').addEventListener('click', function() {
            const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'))
                .map(checkbox => checkbox.value);
            console.log(selectedOrders);
            if (selectedOrders.length > 0) {
                // Создаем скрытую форму для отправки данных
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('orders.selected') }}";

                // Добавляем CSRF токен
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Добавляем выбранные идентификаторы заказов
                const orderIdsInput = document.createElement('input');
                orderIdsInput.type = 'hidden';
                orderIdsInput.name = 'ids';
                orderIdsInput.value = selectedOrders.join(',');
                form.appendChild(orderIdsInput);

                // Добавляем форму в документ и отправляем
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Пожалуйста, выберите хотя бы один заказ.');
            }
        });
    </script>
@endpush
