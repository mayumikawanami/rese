<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MypageController extends Controller
{
    public function show (Request $request)
    {
        // ログインユーザーの予約情報を取得
        $reservations = session('reservation_details', []);
        $user = Auth::user();
        $shops = $user->favorites;
        $reservations = $user->reservations;


        return view('mypage', compact('shops', 'reservations'));
    }
}
