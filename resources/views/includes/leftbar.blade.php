<div class="menu-content h-100" data-simplebar>
                <ul class="metismenu left-sidenav-menu">
                    <li class="menu-label mt-0">Main</li>
                    <li>
                        <a href="javascript: void(0);"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Продукты</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="ti-control-record"></i>Вход</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="ti-control-record"></i>Продукты</a></li> 
                            <li class="nav-item"><a class="nav-link" href="{{ route('products.create') }}"><i class="ti-control-record"></i>Добавить продукт</a></li> 
                        </ul>
                    </li>
    
                    <hr class="hr-dashed hr-menu">

                    <li class="menu-label mt-0">Пользователи</li>
                    <li>
                        <a href="javascript: void(0);"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Пользователи</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i class="ti-control-record"></i>Пользователи</a></li>
                            @can('create', App\Models\User::class)
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.create') }}"><i class="ti-control-record"></i>Добавить пользователя</a></li>
                            @endcan
                        </ul>
                    </li>   

                    <hr class="hr-dashed hr-menu">

                    <li class="menu-label mt-0">Администрирование</li>

                    <li>
                        <a href="javascript: void(0);"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Полномочия и роли</span><span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="{{ route('permissions') }}"><i class="ti-control-record"></i>Полномочия</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('roles') }}"><i class="ti-control-record"></i>Роли</a></li>
                        </ul>
                    </li>


                    <li class="menu-label my-2">Коммент внизу</li>
                </ul>
            </div>
        </div>
        <!-- end left-sidenav-->
        