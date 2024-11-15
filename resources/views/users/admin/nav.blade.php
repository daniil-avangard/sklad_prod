@can('update', \App\Models\User::class)
<div class="pb-4">
    <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ is_active('users.show') }}" href="{{ route('users.show', $user) }}">Права</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ is_active('users.edit') }}" href="{{ route('users.edit', $user) }}">Настройки</a>
        </li>
    </ul>
</div><!--end card-body-->
@endcan
