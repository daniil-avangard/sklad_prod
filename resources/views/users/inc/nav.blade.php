<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}"
                href="{{ route('user.settings') }}">Настройки</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.orders') ? 'active' : '' }}"
                href="{{ route('user.orders') }}">Заказы</a>
        </li>
    </ul>
</div><!--end card-body-->
