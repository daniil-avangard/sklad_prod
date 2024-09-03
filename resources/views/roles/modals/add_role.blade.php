<div class="modal fade" id="add_role_role" tabindex="-1" role="dialog" aria-labelledby="add_role_roleTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="add_role_roleTitle">Добавить роль</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Роль</label>
                                <input type="text" class="form-control" id="name" name="name" required>
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