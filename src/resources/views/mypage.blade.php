@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
@endsection

@section('content')
<div class="mypage__container">
    @if(auth()->check())
    <h2 class="mypage__heading">{{ auth()->user()->name }}さん</h2>
    <div class="mypage__content">
        <div class="mypage__reservation-status">
            <p class="mypage-content_title">予約状況</p>
            <!-- 予約情報表示部分 -->
            @forelse ($reservations as $index => $reservation)
            <div class="reservation-item">
                <label for="modal-toggle-{{ $index }}" class="btn btn-primary">{{ $reservation->reservation_date }} - {{ $reservation->shop->shop_name }}</label>
                <input type="checkbox" id="modal-toggle-{{ $index }}" class="toggle-reservation">
                <div id="myModal{{ $index }}" class="modal">
                    <div class="modal-content">
                        <label for="modal-toggle-{{ $index }}" class="close">&times;</label>
                        <table class="shop-detail__reservation-status">
                            <tr>
                                <th class="shop-detail__reservation-status-item">Shop</th>
                                <td class="td-item">{{ $reservation->shop->shop_name }}</td>
                            </tr>
                            <tr>
                                <th class="shop-detail__reservation-status-item">Date</th>
                                <td class="td-item">{{ $reservation['reservation_date'] }}</td>
                            </tr>
                            <tr>
                                <th class="shop-detail__reservation-status-item">Time</th>
                                <td class="td-item">{{ $reservation['reservation_time'] }}</td>
                            </tr>
                            <tr>
                                <th class="shop-detail__reservation-status-item">Number</th>
                                <td class="td-item">{{ $reservation['number'] }}人</td>
                            </tr>
                        </table>
                        <!-- 予約情報の削除フォーム -->
                        <form action="{{ route('reservation.delete', ['id' => $reservation->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">予約をキャンセル</button>
                        </form>
                        <!-- 日時と人数の変更フォーム -->
                        <form action="{{ route('reservation.update', ['id' => $reservation->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <!-- 日時の選択 -->
                            <div class="form-group">
                                <label for="reservation_date_{{ $reservation->id }}">日付</label>
                                <select id="reservation_date_{{ $reservation->id }}" name="reservation_date" class="form-control">
                                    <!-- 日付のオプションを動的に生成 -->
                                    @for ($i = 0; $i < 30; $i++) <option value="{{ \Carbon\Carbon::now()->addDays($i)->format('Y-m-d') }}" {{ \Carbon\Carbon::now()->addDays($i)->format('Y-m-d') == $reservation->reservation_date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::now()->addDays($i)->format('Y-m-d') }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="reservation_time_{{ $reservation->id }}">時間</label>
                                <select id="reservation_time_{{ $reservation->id }}" name="reservation_time" class="form-control">
                                    <!-- 時間のオプションを動的に生成 -->
                                    @for ($hour = 0; $hour < 24; $hour++) @for ($minute=0; $minute < 60; $minute+=30) <option value="{{ sprintf('%02d:%02d:00', $hour, $minute) }}" {{ sprintf('%02d:%02d:00', $hour, $minute) == $reservation->reservation_time ? 'selected' : '' }}>
                                        {{ sprintf('%02d:%02d', $hour, $minute) }}
                                        </option>
                                        @endfor
                                        @endfor
                                </select>
                            </div>

                            <!-- 人数の選択 -->
                            <div class="form-group">
                                <label for="number_{{ $reservation->id }}">人数</label>
                                <select id="number_{{ $reservation->id }}" name="number" class="form-control">
                                    @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}" {{ $i == $reservation->number ? 'selected' : '' }}>{{ $i }}人</option>
                                        @endfor
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">予約を変更</button>
                        </form>
                        <div class="mypage__rating-form">
                            <p class="mypage-content_title">評価とコメントを追加</p>
                            <form action="{{ route('ratings.store') }}" method="POST">
                                @csrf
                                <!-- ユーザーが評価する店舗の選択 -->
                                <div class="form-group">
                                    <label for="shop_id">店舗名</label>
                                    <select id="shop_id" name="shop_id" class="form-control">
                                        @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- 評価の選択 -->
                                <div class="form-group">
                                    <label for="rating">評価（1から5までの数字）:</label>
                                    <input type="number" name="rating" min="1" max="5">
                                    @error('rating')
                                    <p class="rating__error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- コメント入力欄 -->
                                <div class="form-group">
                                    <label for="comment">コメント:</label>
                                    <textarea name="comment" rows="4" cols="50"></textarea>
                                    @error('comment')
                                    <p class="rating__error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- 送信ボタン -->
                                <button type="submit" class="btn btn-primary">評価とコメントを送信</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p>予約はありません</p>
            @endforelse
        </div>
        @if (session('status'))
        <p>{{ session('status') }}</p>
        @endif
        <div class="mypage__favorite-shops">
            <p class="mypage-content_title">お気に入り店舗</p>
            @foreach($shops as $shop)
            <div class="mypage__favorite-shop__wrapper">
                <div class="mypage__favorite-shop__item">
                    <div class="mypage__favorite-shop__image">
                        <img src="{{ $shop->photo_url }}" alt="{{ $shop->shop_name }}">
                    </div>
                    <div class="mypage__favorite-shop__content">
                        <h2 class="mypage__favorite-shop__name">{{ $shop->shop_name }}</h2>
                        <div class="mypage__favorite-shop__tag">
                            <p class="mypage__favorite-shop__area">#{{ $shop->area }}</p>
                            <p class="mypage__favorite-shop__genre">#{{ $shop->genre }}</p>
                        </div>
                        <div class="mypage__favorite-shop__details-with-favorite">
                            <a href="{{ route('shops.detail', ['id' => $shop->id]) }}" class="mypage__favorite-shop__detail-link">詳しくみる</a>
                            <form action="{{ route('favorite.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                <button type="submit" class="mypage__favorite-shop__favorite-button
                                @if(auth()->check() && auth()->user()->favorites->contains($shop->id))
                                    shop-container__favorite-button--red
                                @else
                                    shop-container__favorite-button--gray
                                @endif">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <p>ログインしてください。</p>
    @endif
</div>
@endsection