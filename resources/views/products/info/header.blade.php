@include('products.inc.nav')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="" class=" mx-auto  d-block"
                            height="300">
                    </div><!--end col-->
                    <div class="col-lg-6 align-self-center">
                        <div class="single-pro-detail">
                            <h3 class="pro-title">{{ $product->name }}</h3>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <b>Артикул: </b>
                                        {{ $product->sku }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <b>Категория: </b>
                                        {{ $product->category->name }}
                                    </div>
                                    <div class="mb-3">
                                        <b>Компания: </b>
                                        {{ $product->company->name }}
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-muted font-13">Необходимость размещения в точках продаж:</h5>
                            <div class="row">
                                <div class="col-6">
                                    <h6>ККО:</h6>
                                    <ul class="list-unstyled  border-0">
                                        <li class="mb-2"><b>Оперзал: </b>
                                            {!! kko_express_check($product->kko_hall) !!}
                                        </li>
                                        <li class="mb-2">
                                            <b>Открытие счетов: </b>
                                            {!! kko_express_check($product->kko_account_opening) !!}
                                        </li>
                                        <li class="mb-2">
                                            <b>Менеджерам: </b>
                                            {!! kko_express_check($product->kko_manager) !!}
                                        </li>
                                        <li class="mb-2">
                                            <b>Операционистам: </b>
                                            {!! kko_express_check($product->kko_operator) !!}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <h6>Экспресс:</h6>
                                    <ul class="list-unstyled  border-0">
                                        <li class="mb-2"><b>Оперзал: </b>
                                            {!! kko_express_check($product->express_hall) !!}
                                        </li>
                                        <li class="mb-2">
                                            <b>Операционистам: </b>
                                            {!! kko_express_check($product->express_operator) !!}
                                        </li>
                                    </ul>

                                </div>

                                <h6 class="text-muted font-13">Описание:</h6>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
