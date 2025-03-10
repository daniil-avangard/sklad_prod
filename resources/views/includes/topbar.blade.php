<!-- Top Bar Start -->
<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            {{-- <li class="dropdown hide-phone">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i data-feather="search" class="topbar-icon"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-lg p-0">
                    <!-- Top Search Bar -->
                    <div class="app-search-topbar">
                        <form action="#" method="get">
                            <input type="search" name="search" class="from-control top-search mb-0"
                                placeholder="Type text...">
                            <button type="submit"><i class="ti-search"></i></button>
                        </form>
                    </div>
                </div>
            </li> --}}

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i data-feather="bell" class="align-self-center topbar-icon"></i>
                    <span class="badge bg-danger rounded-pill noti-icon-badge">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg pt-0">

                    <h6
                        class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        Уведомления <span class="badge bg-primary rounded-pill">2</span>
                    </h6>
                    <div class="notification-menu" data-simplebar>
                        <!-- item-->
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">2 мин</small>
                            <div class="media">
                                <div class="avatar-md bg-soft-primary">
                                    <i data-feather="shopping-cart" class="align-self-center icon-xs"></i>
                                </div>
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Новое уведомление</h6>
                                    <small class="text-muted mb-0">В разработке</small>
                                </div><!--end media-body-->
                            </div><!--end media-->
                        </a><!--end-item-->
                        <!-- item-->
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">10 мин</small>
                            <div class="media">
                                <div class="avatar-md bg-soft-primary">
                                    <img src="/assets/images/users/user-4.jpg" alt=""
                                        class="thumb-sm rounded-circle">
                                </div>
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">В разработке</h6>
                                    <small class="text-muted mb-0">В разработке</small>
                                </div><!--end media-body-->
                            </div><!--end media-->
                        </a><!--end-item-->
                        <!-- item-->
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">40 мин</small>
                            <div class="media">
                                <div class="avatar-md bg-soft-primary">
                                    <i data-feather="users" class="align-self-center icon-xs"></i>
                                </div>
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Новый пользователь</h6>
                                    <small class="text-muted mb-0">В разработке</small>
                                </div><!--end media-body-->
                            </div><!--end media-->
                        </a><!--end-item-->
                        <!-- item-->
                        <!-- item-->
                        <a href="#" class="dropdown-item py-3">
                            <small class="float-end text-muted ps-2">2 часа</small>
                            <div class="media">
                                <div class="avatar-md bg-soft-primary">
                                    <i data-feather="check-circle" class="align-self-center icon-xs"></i>
                                </div>
                                <div class="media-body align-self-center ms-2 text-truncate">
                                    <h6 class="my-0 fw-normal text-dark">Шото там</h6>
                                    <small class="text-muted mb-0">В разработке</small>
                                </div><!--end media-body-->
                            </div><!--end media-->
                        </a><!--end-item-->
                    </div>
                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                        Посмотреть все <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="ms-1 nav-user-name hidden-sm">{{ Auth::user()->surname }}
                        {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }}</span>
                    <img src="/assets/images/users/user-5.jpg" alt="profile-user" class="rounded-circle thumb-xs" />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('user.orders') }}"><i data-feather="shopping-cart"
                            class="align-self-center icon-xs icon-dual me-1"></i>Заказы</a>
                    <a class="dropdown-item" href="{{ route('user.settings') }}"><i data-feather="settings"
                            class="align-self-center icon-xs icon-dual me-1"></i>Настройки</a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"><i data-feather="power"
                            class="align-self-center icon-xs icon-dual me-1"></i>Выйти</a>
                </div>
            </li>
            <li id ="shop-cart" class="dropdown" style="width: 57px; height: 57px; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" style="transform: scale(1.35) translateY(8px);">
                    <g id="trolley">
                        <path stroke="#7081b9" fill="#7081b9" d="M29.4,8.85A2.48,2.48,0,0,0,27.53,8H14a1,1,0,0,0,0,2H27.53a.47.47,0,0,1,.36.16.48.48,0,0,1,.11.36l-1.45,10A1.71,1.71,0,0,1,24.85,22H14.23a1.72,1.72,0,0,1-1.68-1.33L10,8.79v0h0L9.5,6.87A3.79,3.79,0,0,0,5.82,4H3A1,1,0,0,0,3,6H5.82A1.8,1.8,0,0,1,7.56,7.36L8,9.21H8L10.6,21.09A3.72,3.72,0,0,0,14.23,24H24.85a3.74,3.74,0,0,0,3.68-3.16l1.45-10A2.45,2.45,0,0,0,29.4,8.85Z" />
                        <path stroke="#7081b9" fill="#7081b9" d="M16,25H14a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                        <path stroke="#7081b9" fill="#7081b9" d="M25,25H23a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                        <text x="16" y="19" font-size="10" fill="#7081b9">0</text>
                    </g>
                </svg>
                <div id="block-cart" class="dropdown-menu dropdown-menu-end dropdown-lg pt-0">
                    <h6
                        class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        Корзина <span class="badge bg-primary rounded-pill">0</span>
                    </h6>
                    <h6
                        class="dropdown-item-text font-15 m-0 py-3 d-flex justify-content-between align-items-center">
                        Итого <span class="badge bg-primary rounded-pill">0</span>
                    </h6>
                </div>
            </li>
        </ul><!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>
            <li class="creat-btn">
                <div class="nav-link">
                    <a class=" btn btn-sm btn-soft-primary" href="#" role="button"><i
                            class="fas fa-plus me-2"></i>Создать жалобу или предложить улучшение</a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->
