<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    public function create(Request $request)
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        // ユーザーの作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 新規ユーザーに 'user' 役割を割り当てる
        $user->assignRole('user');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('thanks');

    }

    public function showThanks()
    {
        return view('thanks');
    }

    public function resendVerificationEmail()
    {
        // メール再送信が成功した場合
        session()->with('message');

        return redirect()->route('thanks');
    }
}
