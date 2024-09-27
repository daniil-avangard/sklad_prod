<div class="d-flex justify-content-between align-items-center">
    <h3>Варианты</h3>
    <a href="{{ route('products.variants.create', $product) }}" class="btn btn-primary">Добавить вариант</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Артикул</th>
            <th>Дата актуализации</th>
            <th>Количество</th>
            <th>Зарезервировано</th>
            <th>Зарезервировано для заказа</th>
            <th>Доступно к заказу</th>
            <th>Макет</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($variants as $variant)
            <tr>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->date_of_actuality ? date('d.m.Y', strtotime($variant->date_of_actuality)) : '' }}</td>
                <td>{{ $variant->quantity }}</td>
                <td>{{ $variant->reserved }}</td>
                <td>{{ $variant->reserved_order }}</td>
                <td>{{ $variant->is_active ? 'Да' : 'Нет' }} ({{ $variant->quantity - $variant->reserved }})</td>
                <td>
                    @if ($variant->image)
                        <a href="{{ asset('storage/' . $variant->image) }}" target="_blank">Макет</a>
                    @else
                        <span class="text-muted">Нет макета</span>
                    @endif
                </td>
                <td>
                    @can('update', App\Models\ProductVariant::class)
                        <a href="{{ route('products.variants.edit', ['product' => $product, 'variant' => $variant]) }}"
                            class="btn btn-primary">Редактировать</a>
                    @endcan
                    @can('delete', App\Models\ProductVariant::class)
                        <x-form
                            action="{{ route('products.variants.delete', ['product' => $product, 'variant' => $variant]) }}"
                            method="DELETE" style="display: inline-block;">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </x-form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
