@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/dashboard.css')}}">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="shop-manager__container">
        <h2 class="shop-manager__container-title">店舗管理</h2>
        <div class="shop-manager__container-link">
            <a href="{{ route('shopManager.shops') }}" class="btn btn-primary">店舗情報の作成</a>
            <a href="{{ route('shopManager.reservations') }}" class="btn btn-primary">予約情報を確認</a>
        </div>
    </div>
    <div class="notification-mail__container">
        <h2>お知らせメール送信フォーム</h2>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('shopManager.sendNotificationMail') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="reservation_id">予約日から送信者を選択</label>
                <select name="reservation_id" class="form-control" required>
                    <option value="">予約を選択してください</option>
                    @foreach($reservations as $reservation)
                    <option value="{{ $reservation->id }}">{{ $reservation->shop->shop_name }} - {{ $reservation->reservation_date }} - {{ $reservation->reservation_time }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="content">お知らせ内容</label>
                <textarea name="content" class="form-control" rows="5" placeholder="お知らせ内容を入力してください" required></textarea>
            </div>
            <button type="submit" class="email-send__button">送信</button>
        </form>
    </div>
</div>
@endsection