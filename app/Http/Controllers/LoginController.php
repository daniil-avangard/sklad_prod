<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function store(LoginRequest $loginRequest)
    {
        $email = $loginRequest->input('email');
        $password = $loginRequest->input('password');
        $remember = $loginRequest->has('remember');

        // Находим пользователя по email без учета регистра
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])
                    ->where('is_admin', true)
                    ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'Неверный логин или пароль'
            ]);
        }

        Auth::login($user, $remember);

        $loginRequest->session()->regenerate();
        Cookie::queue('selectSkladDivision', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladOrderStatus', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladProductOrder', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladCheckBoxBlock', '', time()+3600, null, null, false, false);
        Cookie::queue('selectSkladIDOrder', '', time()+3600, null, null, false, false);
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