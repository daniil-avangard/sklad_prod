<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="card-title">Список доступных подразделений</h4>
            </div><!--end col-->
            <div class="col-auto">
                <button class="btn {{ $isAllDivisionsSelected ? 'btn-danger' : 'btn-primary' }}" id="add-all-divisions"
                    data-is-all-selected="{{ $isAllDivisionsSelected ? 1 : 0 }}">
                    {{ $isAllDivisionsSelected ? 'Удалить все' : 'Добавить все' }}
                </button>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-header-->

    <div class="ps-3 card-body list-group custom-list-group" id="division-list" data-product-id="{{ $product->id }}">
        @foreach ($allDivisions as $divisionCategory)
            <div class="list-group-item">
                <h4 class="division__item-category card-title mb-2 pb-1 {{ $divisionCategory['category_division_selected'] ? 'text-primary' : 'text-muted' }}"
                    data-division-category-id="{{ $divisionCategory['category_id'] }}"
                    data-is-category-selected="{{ $divisionCategory['category_division_selected'] ? 1 : 0 }}">
                    {{ $divisionCategory['category_name'] }}
                </h4>

                <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2">
                    @foreach ($divisionCategory['divisions'] as $divisionItem)
                        <li class="division__item p-2 ps-4 pe-4 rounded text-center border
                            {{ $divisionItem['is_active'] ? 'border-primary' : 'border-dark-subtle' }}"
                            data-division-id="{{ $divisionItem['division']->id }}">
                            {{ $divisionItem['division']->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div><!--end card-body-->
</div><!--end card-->
