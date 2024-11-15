@can('update', \App\Models\User::class)
<div class="tab-pane fade show active" id="Role_Permission" role="tabpanel" aria-labelledby="Role_Permission_tab">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body  report-card">
                            <div class="row">
                                <div class="col d-flex justify-content-between">
                                    <p class="text-dark mb-1 fw-semibold">Список Полномочий</p>
                                    <button type="button" class="col-auto align-self-center btn btn-primary"
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

                                                    <x-form
                                                        action="{{ route('user.permissions.detach', [$user, $permission]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="permission_id"
                                                            value="{{ $permission->id }}">
                                                        <button
                                                            onclick="return confirm('Вы уверены, что хотите удалить это полномочие?')"
                                                            type="submit" class="btn btn-danger">Удалить</button>
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
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body  report-card">
                            <div class="row">
                                <div class="col d-flex justify-content-between">
                                    <p class="text-dark mb-1 fw-semibold">Список Ролей</p>
                                    <button type="button" class="col-auto align-self-center btn btn-primary"
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
                                                    <x-form action="{{ route('user.roles.detach', [$user, $role]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="role_id"
                                                            value="{{ $role->id }}">
                                                        <button
                                                            onclick="return confirm('Вы уверены, что хотите удалить эту роль?')"
                                                            type="submit" class="btn btn-danger">Удалить</button>
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

                @include('users.admin.groupsDivisions')


            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            tutu3
        </div>

    </div><!--end col-->
</div><!--end row-->
@endcan
