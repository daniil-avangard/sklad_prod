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

    <x-form method="POST" action="{{ route('login.store') }}">


        <div class="form-group mb-2">
            <label class="form-label" for="email">Почта</label>
            <div class="input-group">
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" id="email"
                    placeholder="Введите ваш email">
            </div>
        </div><!--end form-group-->

        <!-- <h1 class="" style="color: green">
            ЧТо-то написал Я
        </h1> -->

        <div class="form-group mb-2">
            <label class="form-label" for="userpassword">Пароль</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Введите пароль">
            </div>
        </div><!--end form-group-->

        <div class="form-group row my-3">
            <div class="col-sm-6">
                <div class="custom-control custom-switch switch-success">
                    <input type="checkbox" class="custom-control-input" value="1" name="remember"
                        id="customSwitchSuccess">
                    <label class="form-label text-muted" for="customSwitchSuccess">Запомнить меня</label>
                </div>
            </div><!--end col-->
            <div class="col-sm-6 text-end">
                <a href="auth-recover-pw.html" class="text-muted font-13"><i class="dripicons-lock"></i> Забыли пароль?</a>
            </div><!--end col-->
        </div><!--end form-group-->

        <div class="form-group mb-0 row">
            <div class="col-12">
                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Войти <i
                        class="fas fa-sign-in-alt ms-1"></i></button>
            </div><!--end col-->
        </div> <!--end form-group-->
    </x-form>
    <div class="m-3 text-center text-muted">
        <p class="mb-0">У вас нет аккаунта <a href="auth-register.html" class="text-primary ms-2">Зарегистрироваться</a>
        </p>
    </div>
@endsection
