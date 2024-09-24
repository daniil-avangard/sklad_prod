<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserSettings\UpdateDataRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserSettings\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;

class UserSettingsController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
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


    public function orders()
    {
        $user = User::find(Auth::user()->id);
        $orders = $user->orders;
        return view('users/orders.index', compact('user', 'orders'));
    }

    public function order(Order $order)
    {
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        $orderItems->load('product');
        $user = User::find(Auth::user()->id);
        return view('users/orders/show', compact('order', 'orderItems', 'user'));
    }
}
