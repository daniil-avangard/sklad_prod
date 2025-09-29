@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Профиль',
        'route' => 'user.settings',
        'breadcrumbs' => $user->surname . ' ' . $user->first_name . ' ' . $user->middle_name,
    ])
    @include('users.inc.head')

    @include('users.inc.nav')

    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">


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
                                    <x-form method="PUT" action="{{ route('user.settings.update') }}" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label
                                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Фамилия</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" name="surname" type="text"
                                                    value="{{ $user->surname }}">
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
                                            <label
                                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Отчество</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" name="middle_name" type="text"
                                                    value="{{ $user->middle_name }}">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label
                                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Телефон</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="las la-phone"></i></span>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ $user->phone }}" placeholder="Phone"
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Аватар</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input type="file" accept="image/*" class="form-control" name="avatar" id="avatarInput" style="display:none">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="avatarSelectBtn">Выбрать файл</button>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="delete_avatar" value="1" id="deleteAvatar">
                                                    <label class="form-check-label" for="deleteAvatar">
                                                        Удалить текущий аватар
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Почта</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="las la-at"></i></span>
                                                    <input type="text" class="form-control" disabled
                                                        value="{{ $user->email }}" placeholder="Email"
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-primary">Сохранить</button>
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
                                    <x-form method="PUT" action="{{ route('user.settings.updatePassword') }}">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Текущий
                                                пароль</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" type="password" name="password"
                                                    placeholder="Пароль">
                                                {{-- <a href="#" class="text-primary font-12">Забыли пароль? </a> --}}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Новый
                                                пароль</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" type="password" name="new_password"
                                                    placeholder="Новый пароль">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Введите еще
                                                раз
                                                пароль</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" type="password" name="new_password_confirmation"
                                                    placeholder="Еще раз">
                                                <span class="form-text text-muted font-12">Не сообщаяте свой пароль ни
                                                    кому</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Сменить
                                                    пароль</button>
                                            </div>
                                        </div>
                                    </x-form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!-- end col -->
                    </div><!--end row-->
                </div><!--end tab-pane-->

            </div>
        </div>
    </div>

    </div><!--end row-->
@endsection
@push('scripts-plugins')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trigger = document.querySelector('.dastone-profile_main-pic-change');
            const fileInput = document.getElementById('avatarInput');
            const selectBtn = document.getElementById('avatarSelectBtn');
            const preview = document.getElementById('avatarPreview');

            function openPicker() {
                if (fileInput) fileInput.click();
            }

            if (trigger) {
                trigger.addEventListener('click', function(e){
                    e.preventDefault();
                    openPicker();
                });
            }
            if (selectBtn) {
                selectBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    openPicker();
                });
            }
            if (fileInput) {
                fileInput.addEventListener('change', function(){
                    if (fileInput.files && fileInput.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (preview) preview.src = e.target.result;
                        };
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                });
            }
        });
    </script>
@endpush
