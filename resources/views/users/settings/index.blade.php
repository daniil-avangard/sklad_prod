@extends('layouts.base')

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Профиль',
        'route' => 'user.settings',
        'breadcrumbs' => $user->surname . ' ' . $user->first_name . ' ' . $user->middle_name,
    ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dastone-profile">
                        <div class="row">
                            <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                <div class="dastone-profile-main">
                                    <div class="dastone-profile-main-pic">
                                        <img src="/assets/images/users/user-4.jpg" alt="" height="110"
                                            class="rounded-circle">
                                        <span class="dastone-profile_main-pic-change">
                                            <i class="fas fa-camera"></i>
                                        </span>
                                    </div>
                                    <div class="dastone-profile_user-detail">
                                        <h5 class="dastone-user-name"> {{ $user->surname }} {{ $user->first_name }}
                                            {{ $user->middle_name }}</h5>
                                        <p class="mb-0"><b>Должность: </b>{{ $user->position }}</p>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-4 ms-auto align-self-center">
                                <ul class="list-unstyled personal-detail mb-0">
                                    <li class=""><i class="ti ti-mobile me-2 text-secondary font-16 align-middle"></i>
                                        <b> Телефон </b> : {{ $user->phone }}
                                    </li>
                                    <li class="mt-2"><i class="ti ti-email text-secondary font-16 align-middle me-2"></i>
                                        <b> Email </b> : {{ $user->email }}
                                    </li>
                                    </li>
                                </ul>

                            </div><!--end col-->
                            <div class="col-lg-4 align-self-center">
                                {{-- <div class="row">
                                    <div class="col-auto text-end border-end">
                                        <button type="button"
                                            class="btn btn-soft-primary btn-icon-circle btn-icon-circle-sm mb-2">
                                            <i class="fab fa-facebook-f"></i>
                                        </button>
                                        <p class="mb-0 fw-semibold">Facebook</p>
                                        <h4 class="m-0 fw-bold">25k <span
                                                class="text-muted font-12 fw-normal">Followers</span></h4>
                                    </div><!--end col-->
                                    <div class="col-auto">
                                        <button type="button"
                                            class="btn btn-soft-info btn-icon-circle btn-icon-circle-sm mb-2">
                                            <i class="fab fa-twitter"></i>
                                        </button>
                                        <p class="mb-0 fw-semibold">Twitter</p>
                                        <h4 class="m-0 fw-bold">58k <span
                                                class="text-muted font-12 fw-normal">Followers</span></h4>
                                    </div><!--end col-->
                                </div><!--end row--> --}}
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
                <a class="nav-link active" id="settings_detail_tab" data-bs-toggle="pill"
                    href="#Profile_Settings">Настройки</a>
            </li>
        </ul>
    </div><!--end card-body-->


    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">


                <div class="tab-pane fade show active" id="Profile_Settings" role="tabpanel"
                    aria-labelledby="settings_detail_tab">
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
                                    <x-form method="PUT" action="{{ route('user.settings.update') }}">
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
                                                <input class="form-control" type="password"
                                                    name="new_password_confirmation" placeholder="Еще раз">
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
