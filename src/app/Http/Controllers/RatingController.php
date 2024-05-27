<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RatingRequest;
use App\Models\Rating;

class RatingController extends Controller
{
    public function store(RatingRequest $request)
    {
        Rating::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', '評価を送信しました。');
    }
}
