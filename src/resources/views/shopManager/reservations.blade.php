@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/reservations.css')}}">
@endsection

@section('content')
<div class="reservations-container">
    <h2 class="reservations-container__title">予約情報</h2>
    <table class="reservations-container__table">
        <thead>
            <tr>
                <th>店舗名</th>
                <th>予約日</th>
                <th>予約時間</th>
                <th>人数</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->shop->shop_name }}</td>
                <td>{{ $reservation->reservation_date }}</td>
                <td>{{ $reservation->reservation_time }}</td>
                <td>{{ $reservation->number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="reservations-button">
        <a class="back-button" href="{{ route('shopManager.dashboard') }}">戻る</a>
    </div>
</div>
@endsection