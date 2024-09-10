
<div class="row">
    <div class="col-12">
    <div class="card">
    <div class="card-body">
    <div class="row">
    <div class="col-lg-6 align-self-center">
    <img src="{{ asset('storage/' . $product->image) }}" alt="" class=" mx-auto  d-block" height="300">                                           
    </div><!--end col-->
    <div class="col-lg-6 align-self-center">
    <div class="single-pro-detail">
    <h3 class="pro-title">{{ $product->name }}</h3>

        
    <h5 class="text-muted font-13">Необходимость размещения в точках продаж:</h5> 
    <div class="row">
    <div class="col-6">
    <h6>ККО:</h6>
    <ul class="list-unstyled  border-0">
    <li class="mb-2"><b>Оперзал: </b> 
    @if($product->kko_hall)     
        <i data-feather="check" style="color: green;"></i>
    @else
        <i data-feather="x" style="color: red;"></i>
    @endif
    </li>
    <li class="mb-2">
    <b>Открытие счетов: </b> 
    @if($product->kko_account_opening)     
        <i data-feather="check" style="color: green;"></i>
    @else
        <i data-feather="x" style="color: red;"></i>
    @endif
    </li>
    <li class="mb-2">
    <b>Менеджерам: </b> 
    @if($product->kko_manager)     
        <i data-feather="check" style="color: green;"></i>
    @else
        <i data-feather="x" style="color: red;"></i>
    @endif
    </li>
    <li class="mb-2">
    <b>Операционистам: </b> 
    @if($product->kko_operator->name === 'no')
    <i data-feather="x" style="color: red;"></i>
    @else     
        {{ $product->kko_operator->name() }}
    @endif
    </li>
    </ul>
    </div>
    <div class="col-6">
    <h6>Экспресс:</h6>
    <ul class="list-unstyled  border-0">
    <li class="mb-2"><b>Оперзал: </b> 
    @if($product->express_hall)     
        <i data-feather="check" style="color: green;"></i>
    @else
        <i data-feather="x" style="color: red;"></i>
    @endif
    </li>
    <li class="mb-2">
    <b>Операционистам: </b>
    @if($product->express_operator->name === 'no')
    <i data-feather="x" style="color: red;"></i>
    @else     
        {{ $product->express_operator->name() }}
    @endif
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