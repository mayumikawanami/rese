<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ReservationController extends Controller
{
    // 予約の作成
    public function create(ReservationRequest $request)
    {
        // 予約の作成ロジックを記述する
    }

    // 予約の更新
    public function update(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);

        if (auth()->id() === $reservation->user_id) {
            $reservation->update([
                'reservation_date' => $request->input('reservation_date'),
                'reservation_time' => $request->input('reservation_time'),
                'number' => $request->input('number'),
            ]);
            return back()->with('status', '予約を変更しました');
        }

        return back()->with('error', '予約の変更に失敗しました');
    }

    // 予約情報の削除
    public function delete($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (auth()->id() === $reservation->user_id) {
            $reservation->delete();
            return back()->with('status', '予約をキャンセルしました');
        }

        return back()->with('error', '予約のキャンセルに失敗しました');
    }

    // 予約情報の変更画面の表示
    public function edit(Request $request, $id)
    {
        // 予約情報の取得
        $reservations = $request->session()->get('reservation_details', []);

        // 指定されたIDの予約情報を取得
        $reservation = $reservations[$id] ?? null;

        // 予約情報が存在しない場合はエラーメッセージを表示
        if (!$reservation) {
            return back()->with('error', '指定された予約が見つかりません');
        }

        // 予約情報の変更画面を表示するビューを返す
        return view('reservation.edit', compact('reservation'));
    }

    // 予約の詳細表示
    public function show($id)
    {
        // セッションから予約情報を取得
        //$reservationDetails = session('reservation_details');
        //$shop = Shop::find($id);
        // 予約情報があれば該当の予約を取得
        //$reservation = null;
        //$shop = null;
        //if ($reservationDetails && isset($reservationDetails['reservation_id'])) {
        //$reservation = Reservation::find($reservationDetails['reservation_id']);
        // 必要なショップデータを取得
        //$shop = Shop::find($reservationDetails['shop_id']);
        //}

        //return view('detail', compact('reservation','reservationDetails','shop'));
        $reservation = Reservation::findOrFail($id);
        $shop = Shop::findOrFail($reservation->shop_id);

        return view('detail', compact('reservation', 'shop'));
    }

    // 予約一覧の表示
    public function index(Request $request)
    {
        // すべての予約を取得してビューに渡す
        $user = Auth::user();
        $reservations = $user->reservations; // ログインユーザーの予約情報を取得

        // セッションから予約情報を取得
        $reservationDetails = session('reservation_details');

        return view('reservations.index', compact('reservations', 'reservationDetails'));
    }

    public function store(ReservationRequest $request)
    {
        if (!Auth::check()) {
            return back()->with('message', '予約をするにはログインが必要です。');
        }
        // ユーザーIDを取得
        $userId = Auth::id();

        // 予約情報をセッションに保存
        $reservationDetails = [
            'shop_id' => $request->shop_id,
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'number' => $request->number,
        ];

        session(['reservation_details' => $reservationDetails]);


        return redirect()->route('shops.detail', ['id' => $request->shop_id])
        ->with('status', 'よろしければ「予約を確定する」をクリックしてください。')
        ->withInput();
    }

    public function submitForm(ReservationRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', '予約をするにはログインが必要です。');
        }
        // フォームから送信された値をセッションに保存
        $request->session()->put('form_data', $request->all());

        // 予約情報をセッションに保存
        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_date = $request->date;
        $reservation->reservation_time = $request->time;
        $reservation->number = $request->number;
        $reservation->save();

        $shop = Shop::find($reservation->shop_id);
        $reservationDetails = [
            'shop_name' => $shop->shop_name,
            'reservation_date' => $reservation->reservation_date,
            'reservation_time' => $reservation->reservation_time,
            'number' => $reservation->number,
        ];
        session(['reservation_details' => $reservationDetails]);

        return redirect()->route('shops.detail', ['id' => $request->shop_id]);
    }

    public function clearSession(Request $request, $id)
    {
        // セッションから予約情報を削除
        Session::forget('reservation_details');

        // セッションデータが削除されたか確認
        if (!session()->has('reservation_details')) {
            $status = '予約情報をリセットしました。';
        } else {
            $status = '予約情報のリセットに失敗しました。';
        }

        // フォームに戻る
        return redirect()->route('shops.detail', ['id' => $id])->with('status', $status);
    }


    public function finalize(Request $request)
    {
        // ユーザーIDを取得
        $userId = Auth::id();

        $reservationDetails = session('reservation_details');

        if (!$reservationDetails || !isset($reservationDetails['shop_id'])) {
            return redirect()->route('shops.index')->with('message', '予約情報が見つかりません。');
        }

        // ユーザーIDを取得
        if (!Auth::check()) {
            return back()->with('message', '予約をするにはログインが必要です。');
        }

        // 予約データを保存
        $reservation = new Reservation();
        $reservation->user_id = $userId;
        $reservation->shop_id = $reservationDetails['shop_id'];
        $reservation->reservation_date = $reservationDetails['reservation_date'];
        $reservation->reservation_time = $reservationDetails['reservation_time'];
        $reservation->number = $reservationDetails['number'];
        $reservation->save();

        // QRコード生成
        $qrCodeData = URL::to('/reservations/' . $reservation->id);
        $qrCode = QrCode::format('svg')->generate($qrCodeData);
        $qrCodePath = 'qr-codes/' . $reservation->id . '.svg';
        Storage::put($qrCodePath, $qrCode);

        // QRコードのパスを保存
        $reservation->qr_code = $qrCodePath;
        $reservation->save();

    // セッションから予約情報を削除
    session()->forget('reservation_details');

    // 予約完了ページにリダイレクトし、予約とショップの情報をセッションに渡す
    return redirect()->route('done');

    }

    public function done()
    {
        return view('done');
    }



    public function detail($id)
    {
        $reservation = Reservation::findOrFail($id);
        $shop = Shop::findOrFail($reservation->shop_id);

        return view('detail', compact('reservation', 'shop'));
    }



    
}



