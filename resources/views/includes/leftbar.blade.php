<div class="menu-content h-100" data-simplebar>
    <ul class="metismenu left-sidenav-menu">
        <li class="menu-label mt-0">Main</li>

        <li>
            <a href="javascript: void(0)">
                <i data-feather="home" class="align-self-center menu-icon"></i><span>Заказ</span><span
                    class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                @can('viewAny', \App\Models\Order::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('orders') }}"><i
                            class="ti-control-record"></i>Заказы</a></li>
                @endcan
                <li class="nav-item"><a class="nav-link" href="{{ route('basket') }}"><i
                            class="ti-control-record"></i>Корзина</a></li>
                @can('create', \App\Models\Order::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('products.list') }}"><i
                            class="ti-control-record"></i>Товары</a></li>
                @endcan
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);"> <i data-feather="home"
                    class="align-self-center menu-icon"></i><span>Продукты</span><span class="menu-arrow"><i
                        class="mdi mdi-chevron-right"></i></span></a>
            <ul class="nav-second-level" aria-expanded="false">
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i
                            class="ti-control-record"></i>Вход</a></li>
                @can('view', \App\Models\Product::class)
                <li class="nav-item"><a class="nav-link" href="{{ route('products') }}"><i
                            class="ti-control-record"></i>Продукты</a></li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories') }}"><i class="ti-control-record"></i>Категории</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('companies') }}"><i
                            class="ti-control-record"></i>Компании</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('divisions') }}"><i
                            class="ti-control-record"></i>Подразделения</a></li>

                <li class="nav-item"><a class="nav-link" href="{{ route('groups.divisions') }}"><i
                            class="ti-control-record"></i>Группы подразделений</a></li>
            </ul>
        </li>

        <hr class="hr-dashed hr-menu">

        <li class="menu-label mt-0">Склад</li>
        <li>
            <a href="javascript: void(0);"> <i data-feather="home"
                    class="align-self-center menu-icon"></i><span>Склад</span><span class="menu-arrow"><i
                        class="mdi mdi-chevron-right"></i></span></a>
            <ul class="nav-second-level" aria-expanded="false">

                <li class="nav-item"><a class="nav-link" href="{{ route('arivals') }}"><i
                            class="ti-control-record"></i>Приход</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('writeoffs') }}"><i
                            class="ti-control-record"></i>Списание</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('assembly') }}"><i
                            class="ti-control-record"></i>Сборка</a></li>
            </ul>
        </li>

        <hr class="hr-dashed hr-menu">

        <li class="menu-label mt-0">Администрирование</li>

        <li>
            <a href="javascript: void(0);"> <i data-feather="home"
                    class="align-self-center menu-icon"></i><span>Полномочия и роли</span><span class="menu-arrow"><i
                        class="mdi mdi-chevron-right"></i></span></a>
            <ul class="nav-second-level" aria-expanded="false">
                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i
                            class="ti-control-record"></i>Пользователи</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('permissions') }}"><i
                            class="ti-control-record"></i>Полномочия</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('roles') }}"><i
                            class="ti-control-record"></i>Роли</a></li>
            </ul>
        </li>


        <li class="menu-label my-2">Коммент внизу</li>
    </ul>
</div>
</div>
<!-- end left-sidenav-->
