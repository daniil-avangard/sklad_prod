

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Список доступных подразделений</h4>
                </div><!--end col-->
                <div class="col-auto">
                    {{-- <a href="{{ route('products.divisions.create', $product) }}" class="btn btn-primary">Добавить подразделение</a> --}}
                </div><!--end col-->
            </div>  <!--end row-->
        </div><!--end card-header-->
        <div class="card-body">
            <div style="max-height: 300px; overflow-y: auto;">
                <button class="btn {{ $isAllDivisionsSelected ? 'btn-danger' : 'btn-primary'}}" id="add-all-divisions">
                    {{ $isAllDivisionsSelected ? 'Удалить все' : 'Добавить все'}}
                </button>

                <ul class="m-0 p-0 list-unstyled d-flex flex-wrap mt-3" id="list-divisions" data-product-id="{{ $product->id }}">
                            @foreach ($allDivisions as $divisionItem)
                                <li class="division__item p-2 ps-4 pe-4 rounded text-center me-3 border
                                        {{ $divisionItem['is_active'] ? 'border-primary' : 'border-dark-subtle' }}"
                                        data-division-id="{{ $divisionItem['division']->id }}"
                                        >
                                    {{ $divisionItem['division']->name }}
                                </li>
                            @endforeach
                </ul>
            </div>
        </div><!--end card-body-->
    </div><!--end card-->
