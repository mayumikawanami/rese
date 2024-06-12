<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;
use App\Mail\ReservationConfirmed;

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
        return view('shopManager.edit_shop', compact('shop'));
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

        return redirect()->route('shopManager.edit_shop',['id' => $id])->with('status', '店舗情報を更新しました');
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
        Log::info('QRコードデータ: ' . $request->input('qr_code_data'));
        $qrCodeData = $request->input('qr_code_data');

        // QRコードのデータから予約IDを取得
        $reservationId = basename($qrCodeData);
        Log::info('予約ID: ' . $reservationId);

        // データベースで予約を検索
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            Log::info('予約が見つかりました: ' . $reservation->id);

            // ステータスを来店済みに更新
            $reservation->status = '来店済み';
            $reservation->save();

            // 予約が見つかった場合は、reservations.blade.php を表示する
            return view('shopManager.reservation_inquiry', ['reservation' => $reservation]);
        } else {
            Log::warning('予約が見つかりません: ' . $reservationId);
            // 予約が見つからなかった場合は、エラーメッセージを表示する
            return view('shopManager.scan', ['error' => '予約が見つかりません。']);
        }
    }

    public function generateQrCode($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        if ($reservation && $reservation->status == '予約確定') {
            $qrCodeData = URL::to('/reservations/' . $reservation->id);
            $qrCode = QrCode::format('svg')->generate($qrCodeData);
            $qrCodePath = 'qr-codes/' . $reservation->id . '.svg';
            Storage::put($qrCodePath, $qrCode);

            // QRコードのパスを保存
            $reservation->qr_code = $qrCodePath;
            $reservation->save();
        }
        return back();
    }

    public function confirmReservation(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status == '予約確定待ち') {
            // ステータスを予約確定に更新
            $reservation->status = '予約確定';
            $reservation->save();

            // ユーザーに予約確定のメールを送信
            Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));

            return redirect()->route('shopManager.reservations')->with('message', '予約が確定され、通知メールが送信されました。');
        }

        return redirect()->route('shopManager.reservations')->with('error', '予約の確定に失敗しました。');
    }

}
