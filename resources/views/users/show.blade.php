@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Профиль',
        'route' => 'users.show',
        'breadcrumbs' => $user->surname . ' ' . $user->first_name . ' ' . $user->middle_name,
    ])
    @include('users.admin.head')
    @include('users.admin.nav')

    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">

                @include('users.admin.role_permission')
            </div>

        </div><!--end tab-content-->
    </div><!--end col-->
    </div><!--end row-->



    <!-- Modal -->
    @include('users.modals.add_permissions_user')
    @include('users.modals.add_roles_user')
    <script>
        function loadData(url, type) {
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text(); // Сначала получаем текст ответа
                })
                .then(text => {
                    return JSON.parse(text); // Затем парсим JSON
                })
                .then(data => {
                    const select = document.getElementById(type === 'permissions' ? 'permission_id' : 'role_id');
                    select.innerHTML = '';
                    const items = type === 'permissions' ? data.permissionsAdd : data.rolesAdd;
                    items.forEach(item => {
                        const option = new Option(item.name, item.id);
                        select.add(option);
                    });
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при загрузке данных. Пожалуйста, попробуйте еще раз.');
                });
        }
    </script>
@endsection
