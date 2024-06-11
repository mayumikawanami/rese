@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/reservations.css')}}">
@endsection

@section('content')
<div class="reservations-container">
    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <h2 class="reservations-container__title">予約情報</h2>
    <table class="reservations-container__table">
        <thead>
            <tr>
                <th>予約者名</th>
                <th>店舗名</th>
                <th>予約日</th>
                <th>予約時間</th>
                <th>人数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->user->name }}</td>
                <td>{{ $reservation->shop->shop_name }}</td>
                <td>{{ $reservation->reservation_date }}</td>
                <td>{{ $reservation->reservation_time }}</td>
                <td>{{ $reservation->number }}</td>
                <td>{{ $reservation->status }}</td>
                <td>
                    @if($reservation->status == '予約確定待ち')
                    <form action="{{ route('shopManager.confirmReservation', $reservation->id) }}" method="POST">
                        @csrf
                        <button class="confirm-button" type="submit">予約を確定する</button>
                    </form>
                    @elseif($reservation->status == '予約確定')
                    @if (Storage::exists($reservation->qr_code))
                    <p class="confirm-status">QRコード発行済み</p>
                    <form action="{{ route('shopManager.generateQrCode', $reservation->id) }}" method="POST">
                        @csrf
                        <button class="reissue-button" type="submit">QRコードの再発行</button>
                    </form>
                    @else
                    <form action="{{ route('shopManager.generateQrCode', $reservation->id) }}" method="POST">
                        @csrf
                        <button class="issue-button" type="submit">QRコードを発行する</button>
                    </form>
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="reservations-button">
        <a class="back-button" href="{{ route('shopManager.dashboard') }}">戻る</a>
    </div>
</div>
@endsection