@extends('layouts.base')

@section('content')
@include('includes.breadcrumb', [
    'title' => 'Роль ' . $role->name, 
    'route' => 'roles.show', 
    'breadcrumbs' => 'Роли',
    'param' => $role,
])

    <x-success />

    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                    <div class="card-body  report-card">
                        <div class="row">
                            <div class="col d-flex justify-content-between">
                                <p class="text-dark mb-1 fw-semibold">Список Полномочий</p>
                                <button type="button" class="col-auto align-self-center btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_permission_role" onclick="loadPermissions({{ $role->id }})">
                                    Добавить
                                </button>
                            </div>
                                
                            <table id="permissions-table" class="bootstable table-responsive">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Полномочия</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                  <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                                <x-form action="{{ route('roles.permissions.detach', $role) }}" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                                    <button type="submit" class="btn btn-danger">Удалить</button>
                                                </x-form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                                         
                </div><!--end col-->
            </div><!--end row-->    

        
        </div><!--end col-->
    </div><!--end row--> 

    @include('roles.modals.add_permissions_role')

    <script>
    function loadPermissions(roleId) {
    fetch(`/roles/${roleId}/permissions/modal`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // Сначала получаем текст ответа
        })
        .then(text => {
            console.log('Ответ сервера:', text); // Выводим ответ в консоль
            return JSON.parse(text); // Затем парсим JSON
        })
        .then(data => {
            const select = document.getElementById('permission_id');
            select.innerHTML = '';
            data.permissionsAdd.forEach(permission => {
                const option = new Option(permission.name, permission.id);
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



