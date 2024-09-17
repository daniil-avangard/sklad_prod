<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserSettings\UpdateDataRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserSettings\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        return view('users/settings.index', compact('user'));
    }

    public function update(UpdateDataRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $user->update($request->all());
        return redirect()->back()->with('success', 'Данные успешно обновлены');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $user->update(['password' => Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Пароль успешно обновлен');
    }
}
