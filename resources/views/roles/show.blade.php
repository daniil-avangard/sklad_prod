@extends('layouts.base')

@section('content')
                  <!-- Page-Title -->
                  <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Роль: {{ $role->name }}</h4>
                                @include('includes.breadcrumb')
                                    </div><!--end col-->
>  
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->

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



