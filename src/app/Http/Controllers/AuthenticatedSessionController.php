<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request)
    {
        return view('login');
    }

    public function store(LoginRequest $request)
    {
        // ユーザーログイン処理
        if (Auth::attempt($request->only('email', 'password'))) {
            // ログイン成功時の処理
            return redirect('/'); // ログイン後に遷移する先を指定
        } else {
            // ログイン失敗時の処理
            return back()->withErrors(['email' => 'ログインに失敗しました。']); // ログインページにエラーを表示
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout(); // ログアウト処理
        $request->session()->invalidate(); // セッションの無効化
        $request->session()->regenerateToken(); // 新しい CSRF トークンの生成
        return redirect('/login'); // ログアウト後に遷移する先を指定
    }

}
