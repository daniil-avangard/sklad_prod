@extends('layouts.base')

@section('content')

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="page-title">Profile</h4>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dastone</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                                            <li class="breadcrumb-item active">Profile</li>
                                        </ol>
                                    </div><!--end col-->
                                    <div class="col-auto align-self-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary" id="Dash_Date">
                                            <span class="day-name" id="Day_Name">Today:</span>&nbsp;
                                            <span class="" id="Select_date">Jan 11</span>
                                            <i data-feather="calendar" class="align-self-center icon-xs ms-1"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i data-feather="download" class="align-self-center icon-xs"></i>
                                        </a>
                                    </div><!--end col-->  
                                </div><!--end row-->                                                              
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">                          
                                <div class="card-body">
                                    <div class="dastone-profile">
                                        <div class="row">
                                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                                <div class="dastone-profile-main">
                                                    <div class="dastone-profile-main-pic">
                                                        <img src="/assets/images/users/user-4.jpg" alt="" height="110" class="rounded-circle">
                                                        <span class="dastone-profile_main-pic-change">
                                                            <i class="fas fa-camera"></i>
                                                        </span>
                                                    </div>
                                                    <div class="dastone-profile_user-detail">
                                                        <h5 class="dastone-user-name"> {{ $user->surname }} {{ $user->first_name }} {{ $user->middle_name }}</h5>                                                        
                                                        <p class="mb-0 dastone-user-name-post">Должность</p>                                                        
                                                    </div>
                                                </div>                                                
                                            </div><!--end col-->
                                            
                                            <div class="col-lg-4 ms-auto align-self-center">
                                                <ul class="list-unstyled personal-detail mb-0">
                                                    <li class=""><i class="ti ti-mobile me-2 text-secondary font-16 align-middle"></i> <b> Телефон </b> : +91 23456 78910</li>
                                                    <li class="mt-2"><i class="ti ti-email text-secondary font-16 align-middle me-2"></i> <b> Email </b> : {{ $user->email }}</li>
                                                    </li>                                                   
                                                </ul>
                                               
                                            </div><!--end col-->
                                            <div class="col-lg-4 align-self-center">
                                                <div class="row">
                                                    <div class="col-auto text-end border-end">
                                                        <button type="button" class="btn btn-soft-primary btn-icon-circle btn-icon-circle-sm mb-2">
                                                            <i class="fab fa-facebook-f"></i>
                                                        </button>
                                                        <p class="mb-0 fw-semibold">Facebook</p>
                                                        <h4 class="m-0 fw-bold">25k <span class="text-muted font-12 fw-normal">Followers</span></h4>
                                                    </div><!--end col-->
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-soft-info btn-icon-circle btn-icon-circle-sm mb-2">
                                                            <i class="fab fa-twitter"></i>
                                                        </button>
                                                        <p class="mb-0 fw-semibold">Twitter</p>
                                                        <h4 class="m-0 fw-bold">58k <span class="text-muted font-12 fw-normal">Followers</span></h4>
                                                    </div><!--end col-->
                                                </div><!--end row-->                                               
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div><!--end f_profile-->                                                                                
                                </div><!--end card-body-->          
                            </div> <!--end card-->    
                        </div><!--end col-->
                    </div><!--end row-->
                    <div class="pb-4">
                        <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="Profile_Post_tab" data-bs-toggle="pill" href="#Profile_Post">Данные</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="settings_detail_tab" data-bs-toggle="pill" href="#Profile_Settings">Настройки</a>
                            </li>
                        </ul>        
                    </div><!--end card-body-->
                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content" id="pills-tabContent">

                                <div class="tab-pane fade show active" id="Profile_Post" role="tabpanel" aria-labelledby="Profile_Post_tab">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="card">
                                                    <div class="card-body  report-card">
                                                        <div class="row">
                                                            <div class="col d-flex justify-content-between">
                                                                <p class="text-dark mb-1 fw-semibold">Список Полномочий</p>
                                                                <button type="button" 
                                                                class="col-auto align-self-center btn btn-primary" 
                                                                data-bs-toggle="modal" data-bs-target="#add_permission_user" 
                                                                onclick="loadData('{{ route('users.permissions.modal', $user->id, false) }}', 'permissions')">
                                                                Добавить Полномочия
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
                                                                    @foreach ($permissions as $permission)
                                                                        <tr>
                                                                            <td>{{ $permission->id }}</td>
                                                                            <td>{{ $permission->name }}</td>
                                                                            <td>
                                                                                
                                                                            <x-form action="{{ route('user.permissions.detach', [$user, $permission]) }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                                                                                <button onclick="return confirm('Вы уверены, что хотите удалить это полномочие?')" type="submit" class="btn btn-danger">Удалить</button>
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
                                                <div class="col-lg-6">     
                                                <div class="card">
                                                    <div class="card-body  report-card">
                                                        <div class="row">
                                                            <div class="col d-flex justify-content-between">
                                                                <p class="text-dark mb-1 fw-semibold">Список Ролей</p>
                                                                <button type="button" 
                                                                class="col-auto align-self-center btn btn-primary" 
                                                                data-bs-toggle="modal" data-bs-target="#add_role_user" 
                                                                onclick="loadData('{{ route('user.roles.modal', $user->id, false) }}', 'roles')">
                                                                    Добавить Роли
                                                                </button>
                                                                </div>
                                                            <table class="bootstable table-responsive">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Роль</th>
                                                                        <th>Действие</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($roles as $role)
                                                                        <tr>
                                                                            <td>{{ $role->id }}</td>
                                                                            <td>{{ $role->name }}</td>
                                                                            <td>
                                                                                <x-form action="{{ route('user.roles.detach', [$user, $role]) }}" method="POST">
                                                                                    @csrf
                                                                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                                                    <button onclick="return confirm('Вы уверены, что хотите удалить эту роль?')" type="submit" class="btn btn-danger">Удалить</button>
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
                                            <div class="row">
                                                tutu3
                                            </div>
                                        
                                        </div><!--end col-->
                                    </div><!--end row-->    
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