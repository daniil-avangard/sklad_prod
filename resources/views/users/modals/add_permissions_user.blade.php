
<div class="modal fade" id="add_permission_user" tabindex="-1" role="dialog" aria-labelledby="add_permission_userTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="add_permission_userTitle">Добавить полномочие</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('user.permissions.attach', $user) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="permission_id" class="form-label">Полномочие</label>
                                <select name="permission_id" id="permission_id" class="form-control mb-3">
                                    <!-- Опции будут добавлены динамически -->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </form>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">                                                      
                <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
