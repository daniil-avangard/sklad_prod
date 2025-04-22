<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0" id="product-info-tabs" role="tablist">
        @can('view', App\Models\ProductVariant::class)
            <li class="nav-item product-info-tab" id="product-info-tab--variants">
                <span class="nav-link nav-link--info active">
                    Варианты
                </span>
            </li>
        @endcan

        @can('create', App\Models\Product::class)
            <li class="nav-item product-info-tab" id="product-info-tab--division">
                <span class="nav-link nav-link--info">
                    Подразделения
                </span>
            </li>
        @endcan
    </ul>
</div><!--end card-body-->
