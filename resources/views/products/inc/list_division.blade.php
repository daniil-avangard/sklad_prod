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

    <div class="
    {{-- d-flex flex-column  --}}
        card-body
        {{-- gap-4 --}}
        list-group custom-list-group">
        @foreach ($allDivisions as $divisionCategory)
            <div>
                <h4 class="card-title text-muted mb-2 pb-1 border-bottom border-color-dark">
                    {{ $divisionCategory['category_name'] }}
                </h4>

                <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-2" id="list-divisions"
                    data-product-id="{{ $product->id }}">

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
