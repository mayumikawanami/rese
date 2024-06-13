<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('status', 'お気に入り機能を使用するにはログインが必要です。');
        }

        $userId = Auth::id();
        $shopId = $request->input('shop_id');

        $favorite = Favorite::where('user_id', $userId)->where('shop_id', $shopId)->first();

        if ($favorite) {
            // 既にお気に入り登録されている場合は削除
            $favorite->delete();
        } else {
            // お気に入り登録
            Favorite::create([
                'user_id' => $userId,
                'shop_id' => $shopId,
            ]);
        }

        return back();
    }
}
