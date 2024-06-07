<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

class ShopManagerController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('shop')->get();
        return view('shopManager.dashboard', compact('reservations'));
    }

    public function showShops()
    {
        $shops = Shop::all();
        return view('shopManager.shops', compact('shops'));
    }

    public function storeShop(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'info' => 'required|string',
            'photo_url' => 'required|url',
        ]);

        $shop = new Shop([
            'shop_name' => $request->shop_name,
            'area' => $request->area,
            'genre' => $request->genre,
            'info' => $request->info,
            'photo_url' => $request->photo_url,
        ]);

        $shop->save();


        return redirect()->route('shopManager.shops')->with('status', '店舗を作成しました');
    }

    public function showReservations()
    {
        $reservations = Reservation::all();
        return view('shopManager.reservations', compact('reservations'));
    }

    public function editShop($id)
    {
        $shop = Shop::findOrFail($id);
        return view('shopManager.editShop', compact('shop'));
    }

    public function updateShop(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'info' => 'required|string',
            'photo_url' => 'required|string',
        ]);

        $shop->update([
            'shop_name' => $request->shop_name,
            'area' => $request->area,
            'genre' => $request->genre,
            'info' => $request->info,
            'photo_url' => $request->photo_url,
        ]);

        return redirect()->route('shopManager.editShop',['id' => $id])->with('status', '店舗情報を更新しました');
    }

    public function dashboard()
    {
        // 必要なデータを取得してビューに渡す
        $reservations = Reservation::with('shop')->get();

        return view('shopManager.dashboard', compact('reservations'));
    }

    public function sendNotificationMail(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'content' => 'required|string',
        ]);

        $reservation = Reservation::find($request->reservation_id);
        $email = $reservation->user->email; // 予約者のメールアドレスを取得
        $content = $request->input('content');

        Mail::to($email)->send(new NotificationMail($content));

        return redirect()->route('shopManager.dashboard')->with('status', 'お知らせメールを送信しました');
    }

    // スキャンフォームを表示
    public function showScanForm()
    {
        return view('shopManager.scan');
    }

    // QRコードをスキャンして予約を確認
    public function scanQrCode(Request $request)
    {
        $qrCodeData = $request->input('qr_code_data');

        // QRコードのデータから予約IDを取得
        $reservationId = basename($qrCodeData);

        // データベースで予約を検索
        $reservation = Reservation::find($reservationId);

        if ($reservation) {
            return view('shopManager.scan', ['reservation' => $reservation, 'message' => '予約が見つかりました。']);
        } else {
            return view('shopManager.scan', ['error' => '予約が見つかりません。']);
        }
    }

}
