@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/reservation_inquiry.css')}}">
@endsection

@section('content')
<div class="reservation-inquiry">
    <h2 class="reservation-inquiry__title">予約照会</h2>
    <table class="reservation-inquiry__table">
        <thead>
            <tr>
                <th>店舗名</th>
                <th>予約日</th>
                <th>予約時間</th>
                <th>人数</th>
                <th>ステータス</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $reservation->shop->shop_name }}</td>
                <td>{{ $reservation->reservation_date }}</td>
                <td>{{ $reservation->reservation_time }}</td>
                <td>{{ $reservation->number }}</td>
                <td>{{ $reservation->status }}</td>
            </tr>
        </tbody>
    </table>
    <div class="reservation-inquiry__button">
        <a class="reservation-inquiry__back-button" href="{{ route('shopManager.dashboard') }}">戻る</a>
    </div>
</div>
@endsection