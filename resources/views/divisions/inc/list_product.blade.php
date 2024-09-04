<div class="col-lg-3">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">                      
                    <h4 class="card-title">Список доступных продуктов</h4>                      
                </div><!--end col-->
                <div class="col-auto"> 
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Добавить продукт</button>
                </div><!--end col-->
            </div>  <!--end row-->                                  
        </div><!--end card-header-->
        <div class="card-body">
            <ul class="list-group custom-list-group mb-n3">
                @foreach ($products as $product)
                <li class="list-group-item align-items-center d-flex justify-content-between">
                    <div class="media">
                        <img src="/assets/images/small/img-1.jpg" height="40" class="me-3 align-self-center rounded" alt="...">
                        <!-- 800x533 Размеры картинки -->
                        <div class="media-body align-self-center"> 
                            <a href="#" class="m-0 d-block fw-semibold font-13">{{ $product->name }}</a>
                            <!-- <a href="#" class="font-12 text-primary">analytic-index.html</a>                                                                                            -->
                        </div><!--end media body-->
                    </div>
                    <!-- <div class="align-self-center">
                        <span class="text-muted mb-n2">20 June</span>
                    </div>                                             -->
                </li>
                @endforeach
            </ul>                                    
        </div><!--end card-body--> 
    </div><!--end card-->
</div><!--end col-->
                                          
</div><!--end col-->