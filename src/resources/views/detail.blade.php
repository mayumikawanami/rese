@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
<div class="shop-detail__container">
    @if(session('success'))
    <div class="alert-success__review">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-success__review">
        {{ session('error') }}
    </div>
    @endif
    <div class="shop-detail__content">
        @if($shop)
        <div class="shop-detail__selected-shop-details @if ($shop->reviews->count() > 0) has-reviews @endif">
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
                <p class="shop-container__shop-area">#{{ $shop->area->name }}</p> <!-- エリア名を表示 -->
                <p class="shop-container__shop-genre">#{{ $shop->genre->name }}</p> <!-- ジャンル名を表示 -->
            </div>
            <p class="shop-detail__info">{{ $shop->info }}</p>

            <div class="all-reviews-button">
                @if ($shop->reviews->count() > 0)
                <!-- ボタンをクリックして別ページにリダイレクト -->
                <a href="{{ route('reviews.index', ['shop' => $shop]) }}" class="view-all-reviews-button">全ての口コミ情報を表示</a>
                @endif
            </div>

            <!-- 口コミ作成ページへのリンクを追加 -->
            @if (auth()->check() && $shop->reviews->where('user_id', auth()->id())->isEmpty() && auth()->user()->hasRole('user'))
            <div class="review__create-button">
                <a href="{{ route('reviews.create', ['id' => $shop->id]) }}" class="review-button">口コミを投稿する</a>
            </div>
            @endif
            @if ($shop->reviews->count() > 0)
            @php $latestReview = $shop->reviews->first(); @endphp
            <div class="review">
                <div class="review-button">
                    @if (auth()->check() && auth()->user()->id == $latestReview->user_id)
                    <!-- 編集ボタン -->
                    <a href="{{ route('reviews.edit', ['shop_id' => $shop->id, 'review_id' => $latestReview->id]) }}" class="edit-button">口コミを編集</a>
                    @endif
                    @if (auth()->check() && (auth()->user()->id == $latestReview->user_id || auth()->user()->hasRole('admin')))
                    <!-- 削除ボタン -->
                    <form action="{{ route('reviews.destroy', ['shop_id' => $shop->id, 'review_id' => $latestReview->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button">口コミを削除</button>
                    </form>
                    @endif
                </div>
                <div class="detail__star-rating">
                    @for ($i = 1; $i <= 5; $i++) @if ($i <=$latestReview->rating)
                        <i class="fas fa-star"></i>
                        @else
                        <i class="far fa-star"></i>
                        @endif
                        @endfor
                </div>
                <div class="detail__review-content">{{ $latestReview->content }}</div>
                @if ($latestReview->image_path)
                <img class="review-img" src="{{ asset('storage/' . $latestReview->image_path) }}" alt="Review Image" style="max-height: 80px;padding: 0 50px" ;>
                @endif
            </div>
            @endif
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