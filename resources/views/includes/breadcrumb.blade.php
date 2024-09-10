 <!-- Page-Title -->
 <div class="row">
<div class="col-sm-12">
    <div class="page-title-box">
        <div class="row">
            <div class="col">
                <h4 class="page-title">{{ $title }}</h4>
                @if(isset($route) && isset($breadcrumbs))
                    {{ Breadcrumbs::render($route, $breadcrumbs, $param ?? null) }}
                @endif
            </div><!--end col-->
            
                <div class="col-auto align-self-center">
                    @if(isset($add_route))
                        <a href="{{ route($add_route) }}" class="btn btn-sm btn-outline-primary">Добавить</a>
                    @endif
                    @if(isset($back_route))
                        <a href="{{ route($back_route, $back_route_param ?? null) }}" class="btn btn-sm btn-outline-primary">Назад</a>
                    @endif
                </div><!--end col-->  

    </div><!--end row-->                                                              
</div><!--end page-title-box-->
</div><!--end col-->
</div><!--end row-->
<!-- end page title end breadcrumb -->
<x-success />
<x-errors />

