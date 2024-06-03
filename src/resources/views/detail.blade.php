@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
<div class="shop-detail__container">
    <div class="shop-detail__content">
        @if($shop)
        <div class="shop-detail__selected-shop-details">
            <div class="shop-detail__nav-buttons">
                @if ($previousShop)
                <a href="{{ route('shops.detail', ['id' => $previousShop->id]) }}" class="shop-detail__nav-button">&lt;</a>
                @else
                <span class="shop-detail__nav-button disabled">&lt;</span>
                @endif

                <h2 class="shop-detail__name">{{ $shop->shop_name }}</h2>

                @if ($nextShop)
                <a href="{{ route('shops.detail', ['id' => $nextShop->id]) }}" class="shop-detail__nav-button">&gt;</a>
                @else
                <span class="shop-detail__nav-button disabled">&gt;</span>
                @endif
            </div>
            <img class="shop-detail__img" src="{{ $shop->photo_url }}" alt="{{ $shop->shop_name }}">
            <div class="shop-detail__tag">
                <p class="shop-detail__area">#{{ $shop->area }}</p>
                <p class="shop-detail__genre">#{{ $shop->genre }}</p>
            </div>
            <p class="shop-detail__info">{{ $shop->info }}</p>
        </div>
        @else
        <p>ショップ情報が見つかりません。</p>
        @endif
        <div class="shop-detail__reservation-form">
            <p class="shop-detail__reservation-form-title">予約</p>
            <form action="{{ route('reservation.submit') }}" method="POST">
                @csrf
                <div class="shop-detail__form-group">
                    <label for="date" class="shop-detail__form-label"></label>
                    <input class="shop-detail__input-date" type="date" id="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}">
                    @error('date')
                    <p class="shop-detail__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="shop-detail__form-group">
                    <label for="time" class="shop-detail__form-label"></label>
                    <select class="shop-detail__input-time" id="time" name="time">
                        <option value="" disabled selected>時間を選択してください</option>
                        @for ($hour = 11; $hour <= 21; $hour++) @for ($minute=0; $minute <=45; $minute +=15) <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" {{ old('time') == sprintf('%02d:%02d', $hour, $minute) ? 'selected' : '' }}>
                            {{ sprintf('%02d:%02d', $hour, $minute) }}
                            </option>
                            @endfor
                            @endfor
                    </select>
                    @error('time')
                    <p class="shop-detail__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="shop-detail__form-group">
                    <label for="number" class="shop-detail__form-label"></label>
                    <select class="shop-detail__select-number" id="number" name="number">
                        <option value="" disabled selected>人数を選択してください</option>
                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ old('number') == $i ? 'selected' : '' }}>{{ $i }}人</option>
                            @endfor
                    </select>
                    @error('number')
                    <p class="shop-detail__error-message">{{ $message }}</p>
                    @enderror
                </div>
                @if (isset($shop))
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <button type="submit" class="shop-detail__reservation-button">予約する</button>
                @else
                <p>Shop data is not available.</p>
                @endif
            </form>

            @if (session('message'))
            <div class="shop-detail__alert-success">
                {{ session('message') }}
            </div>
            @endif
            @if (session('status'))
            <div class="shop-detail__alert-success">
                {{ session('status') }}
            </div>
            @endif

            @php
            $reservationDetails = session('reservation_details');
            @endphp

            @if (!empty($reservationDetails))
            <table class="shop-detail__reservation-status">
                <tr>
                    <th class="shop-detail__reservation-status-item">Shop</th>
                    <td class="td-item">{{ $shop->shop_name }}</td>
                </tr>
                <tr>
                    <th class="shop-detail__reservation-status-item">Date</th>
                    <td class="td-item">{{ $reservationDetails['reservation_date']?? '' }}</td>
                </tr>
                <tr>
                    <th class="shop-detail__reservation-status-item">Time</th>
                    <td class="td-item">{{ $reservationDetails['reservation_time'] ?? ''}}</td>
                </tr>
                <tr>
                    <th class="shop-detail__reservation-status-item">Number</th>
                    <td class="td-item">{{ $reservationDetails['number'] ?? ''}}人</td>
                </tr>
            </table>
            <form action="{{ route('reservation.finalize') }}" method="POST">
                @csrf
                <button type="submit" class="shop-detail__finalize-button">予約を確定する</button>
            </form>
            <a href="{{ route('reservation.clear',['id' => $reservationDetails['shop_id']]) }}" class="btn btn-secondary">戻る</a>
            @endif
        </div>
    </div>
</div>
@endsection