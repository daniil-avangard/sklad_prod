@extends('layouts.auth')

@section('login')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <x-form action="{{ route('register.store') }}" method="POST" class="form-horizontal auth-form">
        <div class="form-group mb-2">
            <label class="form-label" for="surname">Фамилия</label>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ old('surname') }}" name="surname" id="surname"
                    placeholder="Введите вашу фамилию">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="first_name">Имя</label>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ old('first_name') }}" name="first_name" id="first_name"
                    placeholder="Введите ваше имя">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="middle_name">Отчество</label>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ old('middle_name') }}" name="middle_name"
                    id="middle_name" placeholder="Введите ваше отчество">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="email">Email</label>
            <div class="input-group">
                <input type="email" class="form-control" value="{{ old('email') }}" name="email" id="email"
                    placeholder="Введите ваш email">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="position">Должность</label>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ old('position') }}" name="position" id="position"
                    placeholder="Ваша должность">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="password">Пароль</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="password_confirmation">Повторите пароль</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                    placeholder="Введите пароль еще раз">
            </div>
        </div><!--end form-group-->

        <div class="form-group mb-2">
            <label class="form-label" for="mo_number">Телефон</label>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ old('phone') }}" name="phone" id="mo_number"
                    placeholder="Введите ваш телефон">
            </div>
        </div><!--end form-group-->

        <div class="form-group row my-3">
            <div class="col-sm-12">
                <div class="custom-control custom-switch switch-success">
                    <input type="checkbox" class="custom-control-input" id="customSwitchSuccess2">
                    <label class="form-label text-muted" for="customSwitchSuccess2">Я принимаю правила бла бла бла <a
                            href="#" class="text-primary">ссылка на какие то правила или условия. </a></label>
                </div>
            </div><!--end col-->
        </div><!--end form-group-->

        <div class="form-group mb-0 row">
            <div class="col-12">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Зарегистрироваться <i
                        class="fas fa-sign-in-alt ms-1"></i></button>
            </div><!--end col-->
        </div> <!--end form-group-->
    </x-form>

    <div class="m-3 text-center text-muted">
        <p class="mb-0">У вас есть аккаунт? <a href="{{ route('login') }}" class="text-primary ms-2">Войти</a>
        </p>
    </div>

@endsection
