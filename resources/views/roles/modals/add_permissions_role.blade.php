<div class="modal fade" id="add_permission_role" tabindex="-1" role="dialog" aria-labelledby="add_permission_roleTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="add_permission_roleTitle">Добавить роль</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <x-form action="{{ route('roles.permissions.attach', $role) }}" method="POST">
                            <div class="mb-3">
                                <label for="permissions" class="form-label">Полномочия</label>
                                <select name="permission_id" id="permission_id" class="select2 form-control mb-3 custom-select">
                                    <!-- Опции будут добавлены динамически -->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </x-form>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">                                                      
                <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>