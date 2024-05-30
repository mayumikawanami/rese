<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RatingController extends Controller
{
    public function store(RatingRequest $request)
    {
        Rating::create([
            'reservation_id' => $request->reservation_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('mypage')->with('status', '評価を送信しました。');
    }

    public function showMypage()
    {
        $reservations = Reservation::where('user_id', auth()->id())->with('shop')->get();
        $reservationsWithStatus = [];
        foreach ($reservations as $reservation) {
            $reservationDate = Carbon::parse($reservation->reservation_date);
            $isPastReservation = $reservationDate->isPast();
            $reservation->isPastReservation = $isPastReservation;
            $reservationsWithStatus[] = $reservation;
        }
        $user = Auth::user();
        $shops = $user->favorites;

        return view('mypage', compact('reservationsWithStatus','shops'));
    }
}
