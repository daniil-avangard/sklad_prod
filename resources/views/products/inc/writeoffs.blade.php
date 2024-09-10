
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">                      
                    <h4 class="card-title">Списания</h4>                      
                </div><!--end col-->
                <div class="col-auto"> 
                    
                </div><!--end col-->
            </div>  <!--end row-->                                  
        </div><!--end card-header-->
        <div class="card-body">
            <ul class="list-group custom-list-group mb-n3">
                <li class="list-group-item align-items-center d-flex justify-content-between">
                    <div class="media">
                        <div class="media-body align-self-center"> 
                            <span class="m-0 d-block fw-semibold font-13">Дата</span>
                        </div>
                    </div>
                    <div class="align-self-center">
                        <span class="text-muted mb-n2">Кол-во</span>
                    </div>
                    <div class="align-self-center">
                        <span class="text-muted mb-n2">Статус</span>
                    </div>
                </li>
                @foreach ($writeOffs as $writeOff)
                <li class="list-group-item align-items-center d-flex justify-content-between">
                    <div class="media">
                        <div class="media-body align-self-center"> 
                            <a href="#" class="m-0 d-block fw-semibold font-13">{{ \Carbon\Carbon::parse($writeOff['writeOff']->writeoff_date)->format('d.m.Y') }}</a>
                        </div>
                    </div>
                    <div class="align-self-center">
                        <span class="text-muted mb-n2">{{ $writeOff['quantity'] }}</span>
                    </div>
                    <div class="align-self-center">
                        <span class="badge bg-{{ $writeOff['writeOff']->status->color() }}">{{ $writeOff['writeOff']->status->name() }}</span>
                    </div>
                </li>
                @endforeach
            </ul>                                    
        </div><!--end card-body--> 
    </div><!--end card-->