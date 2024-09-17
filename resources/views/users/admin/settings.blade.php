<div class="tab-pane fade" id="Profile_Settings" role="tabpanel" aria-labelledby="settings_detail_tab">
    <div class="row">
        <div class="col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Персональная информация</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Фамилия</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" name="surname" type="text" value="{{ $user->surname }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Имя</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" name="first_name" type="text"
                                value="{{ $user->first_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Отчество</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" name="middle_name" type="text"
                                value="{{ $user->middle_name }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Телефон</label>
                        <div class="col-lg-9 col-xl-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="las la-phone"></i></span>
                                <input type="text" class="form-control" name="phone" value="{{ $user->phone }}"
                                    placeholder="Phone" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Почта</label>
                        <div class="col-lg-9 col-xl-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="las la-at"></i></span>
                                <input type="text" class="form-control" value="{{ $user->email }}"
                                    placeholder="Email" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--end col-->
        <div class="col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Сменить пароль</h4>
                </div><!--end card-header-->
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Текущий пароль</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" type="password" placeholder="Пароль">
                            {{-- <a href="#" class="text-primary font-12">Забыли пароль? </a> --}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Новый пароль</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" type="password" placeholder="Новый пароль">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Введите еще раз
                            пароль</label>
                        <div class="col-lg-9 col-xl-8">
                            <input class="form-control" type="password" placeholder="Еще раз">
                            <span class="form-text text-muted font-12">Не сообщаяте свой пароль ни кому</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-9 col-xl-8 offset-lg-3">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Сменить пароль</button>
                        </div>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!-- end col -->
    </div><!--end row-->
</div><!--end tab-pane-->
