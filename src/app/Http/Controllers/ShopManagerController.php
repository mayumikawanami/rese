<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Mail\NotificationMail;
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
        $areas = Area::pluck('name', 'id');
        $genres = Genre::pluck('name', 'id');
        return view('shopManager.shops', compact('shops', 'areas', 'genres'));
    }

    public function createShop()
    {
        $areas = Area::pluck('name', 'id');
        $genres = Genre::pluck('name', 'id');
        return view('shopManager.create_shop', compact('areas', 'genres'));
    }

    public function storeShop(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'info' => 'required|string',
            'photo_url' => 'required|url',
            'area' => 'required|exists:areas,name',
            'genre' => 'required|exists:genres,name',
        ]);

        $area = Area::where('name', $request->area)->firstOrFail();
        $genre = Genre::where('name', $request->genre)->firstOrFail();

        $shop = new Shop([
            'shop_name' => $request->shop_name,
            'area_id' => $area->id,
            'genre_id' => $genre->id,
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
        $areas = Area::pluck('name', 'id');
        $genres = Genre::pluck('name', 'id');

        return view('shopManager.edit_shop', compact('shop', 'areas', 'genres'));
    }

    public function updateShop(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'info' => 'required|string',
            'photo_url' => 'required|string',
            'area' => 'required|exists:areas,name',
            'genre' => 'required|exists:genres,name',
        ]);

        $area = Area::where('name', $request->area)->firstOrFail();
        $genre = Genre::where('name', $request->genre)->firstOrFail();

        $shop->update([
            'shop_name' => $request->shop_name,
            'area_id' => $area->id,
            'genre_id' => $genre->id,
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
        $qrCodeData = $request->input('qr_code_data');

        // QRコードのデータから予約IDを取得
        $reservationId = basename($qrCodeData);

        // データベースで予約を検索
        $reservation = Reservation::find($reservationId);
        if ($reservation) {

            // ステータスを来店済みに更新
            $reservation->status = '来店済み';
            $reservation->save();

            return view('shopManager.reservation_inquiry', ['reservation' => $reservation]);
        } else {
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
