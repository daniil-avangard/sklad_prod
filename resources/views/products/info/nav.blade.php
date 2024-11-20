{{-- @can('update', \App\Models\User::class) --}}
<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">
        <li class="nav-item">
            @can('view', App\Models\ProductVariant::class)
                <a class="nav-link {{ is_active('products.variants') }}" href="{{ route('products.variants', $product) }}">Варианты</a>
            @endcan
        </li>

        <li class="nav-item">
            @can('create', \App\Models\Product::class)
                <a class="nav-link {{ is_active('products.division') }}" href="{{ route('products.division', $product) }}">Подразделения</a>
            @endcan
        </li>
    </ul>
</div><!--end card-body-->
{{-- @endcan --}}
