<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;



class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function store(LoginRequest $loginRequest)
    {

        $data = $loginRequest->only('email', 'password');
        $remember = $loginRequest->has('remember');

        $data['is_admin'] = true;

        if (!Auth::attempt($data, $remember)) {
            return redirect()->back()->withErrors([
                'email' => 'Неверный логин или пароль'
            ]);
        }

        $loginRequest->session()->regenerate();
        Cookie::queue('selectSkladDivision', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladOrderStatus', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladProductOrder', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladCheckBoxBlock', '', time()+3600, null, null, false, false);
        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}