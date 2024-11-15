
    
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
                <ul class="list-group custom-list-group mb-n3">
                    @foreach ($divisions as $division)
                    <li class="list-group-item align-items-center d-flex justify-content-between">
                            <div class="media-body align-self-center">
                                <a href="#" class="m-0 d-block fw-semibold font-13">{{ $division->name }}</a>
                                <!-- <a href="#" class="font-12 text-primary">analytic-index.html</a>                                                                                            -->
                            </div><!--end media body-->
                            <x-form action="{{ route('products.divisions.removeDivision', [$product, $division]) }}" method="DELETE">
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </x-form>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div><!--end card-body-->
    </div><!--end card-->
