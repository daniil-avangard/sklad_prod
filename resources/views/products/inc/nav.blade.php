<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0">
        <li class="nav-item">
            <a class="nav-link {{ is_active('products.show') }}"
                href="{{ route('products.show', $product->id) }}">Просмотр</a>
        </li>
        @can('update', \App\Models\Product::class)
        <li class="nav-item">
            <a class="nav-link {{ is_active('products.edit') }}"
                href="{{ route('products.edit', $product->id) }}">Редактировать</a>
        </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link {{ is_active('products.arival') }}"
                href="{{ route('products.arival', $product) }}">Приходы</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ is_active('products.writeoff') }}"
                href="{{ route('products.writeoff', $product) }}">Списания</a>
        </li>
    </ul>
</div>
