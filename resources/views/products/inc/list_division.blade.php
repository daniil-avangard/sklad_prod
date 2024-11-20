

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Список доступных подразделений</h4>
                </div><!--end col-->
                <div class="col-auto">
                    <a href="{{ route('products.divisions.create', $product) }}" class="btn btn-primary">Добавить подразделение</a>
                </div><!--end col-->
            </div>  <!--end row-->
        </div><!--end card-header-->
        <div class="card-body">
            <div style="max-height: 300px; overflow-y: auto;">
                <button class="btn btn-primary">Добавить все</button>

                    {{-- <ul class="m-0 p-0 list-unstyled d-flex flex-wrap gap-4 mt-3 justify-content-between"> --}}
                    <ul class="m-0 p-0 list-unstyled d-flex flex-wrap mt-3">
                    @foreach ($divisions as $division)
                        <li class="p-2 ps-4 pe-4 rounded border border-dark-subtle text-center me-3

                        ">
                            {{ $division->name }}
                        </li>

                        {{-- <li class="list-group-item align-items-center d-flex justify-content-between">
                            <x-form action="{{ route('products.divisions.removeDivision', [$product, $division]) }}" method="DELETE">
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </x-form>
                        </li> --}}
                    @endforeach
                </ul>
            </div>
        </div><!--end card-body-->
    </div><!--end card-->
