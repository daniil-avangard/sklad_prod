<div class="col-lg-3">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Список доступных подразделений</h4>                      
                </div><!--end col-->
                <div class="col-auto"> 
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDivisionModal">Добавить подразделение</button>
                </div><!--end col-->
            </div>  <!--end row-->                                  
        </div><!--end card-header-->
        <div class="card-body">
            <ul class="list-group custom-list-group mb-n3">
                @foreach ($divisions as $division)
                <li class="list-group-item align-items-center d-flex justify-content-between">

                        <div class="media-body align-self-center"> 
                            <a href="#" class="m-0 d-block fw-semibold font-13">{{ $division->name }}</a>
                            <!-- <a href="#" class="font-12 text-primary">analytic-index.html</a>                                                                                            -->
                        </div><!--end media body-->
                </li>
                @endforeach
            </ul>                                    
        </div><!--end card-body--> 
    </div><!--end card-->
</div><!--end col-->
                                          
</div><!--end col-->