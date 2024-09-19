<div>
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
                    <x-form action="{{ route('users.update', $user) }}" method="PUT">

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
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ $user->phone }}" placeholder="Phone" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Почта</label>
                            <div class="col-lg-9 col-xl-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="las la-at"></i></span>
                                    <input type="text" name="email" class="form-control"
                                        value="{{ $user->email }}" placeholder="Email" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="division"
                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Поздразделение</label>
                            <div class="col-lg-9 col-xl-8">
                                <select class="form-select" name="division_id" id="division">
                                    <option value="">Выберите поздразделение</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ $division->id == $user->division_id ? 'selected' : '' }}>
                                            {{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Сохранить</button>
                            </div>
                        </div>
                    </x-form>
                </div>
            </div>
        </div> <!--end col-->
        <div class="col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Сменить пароль</h4>
                </div><!--end card-header-->
                <div class="card-body">
                    <x-form action="{{ route('users.updatePassword', $user) }}" method="PUT">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Новый пароль</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="password" name="password" placeholder="Новый пароль">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Введите еще раз
                                пароль</label>
                            <div class="col-lg-9 col-xl-8">
                                <input class="form-control" type="password" name="password_confirmation"
                                    placeholder="Еще раз">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Сменить пароль</button>
                            </div>
                        </div>
                    </x-form>
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!-- end col -->
    </div><!--end row-->
</div><!--end tab-pane-->
