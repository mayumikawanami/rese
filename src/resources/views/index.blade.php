@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
@if (session('status'))
<div class="wrapper__alert-success">
    {{ session('status') }}
</div>
@endif
@if(auth()->check())
<p class="shop-container__user-info">{{ auth()->user()->name }}さん<br>メールアドレス: {{ auth()->user()->email }}</p>
@endif
<div class="shop-container">
    <!-- Sorting Options -->
    <div class="shop-container__sort-search__form">
        @if (auth()->check() && auth()->user()->hasRole('user'))
        <div class="shop-container__sort-form">
            <label for="sort" class="sort">並び替え :</label>
            <div class="dropdown">
                <button class="dropbtn">評価高/低</button>
                <ul class="dropdown-content">
                    <li class="{{ Request::input('sort') == 'default' ? 'selected' : '' }}">
                        <a class="dropdown-list" href="{{ route('shops.index', ['sort' => 'default']) }}">並び替えなし</a>
                    </li>
                    <li class="{{ Request::input('sort') == 'random' ? 'selected' : '' }}">
                        <a class="dropdown-list" href="{{ route('shops.index', ['sort' => 'random']) }}">ランダム</a>
                    </li>
                    <li class="{{ Request::input('sort') == 'high_rating' ? 'selected' : '' }}">
                        <a class="dropdown-list" href="{{ route('shops.index', ['sort' => 'high_rating']) }}">評価が高い順</a>
                    </li>
                    <li class="{{ Request::input('sort') == 'low_rating' ? 'selected' : '' }}">
                        <a class="dropdown-list" href="{{ route('shops.index', ['sort' => 'low_rating']) }}">評価が低い順</a>
                    </li>
                </ul>
            </div>
        </div>

        @endif
        <form action="{{ route('shops.search') }}" method="GET" class="shop-container__search-form">
            <div class="shop-container__form-group">
                <label for="area" class="shop-container__form-label shop-container__form-label--area"></label>
                <select name="area" id="area" class="shop-container__form-select">
                    <option value="">All area　</option>
                    @foreach($areas as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                    @endforeach
                </select>
            </div>
            <div class="shop-container__form-group">
                <label for="genre" class="shop-container__form-label shop-container__form-label--genre"></label>
                <select name="genre" id="genre" class="shop-container__form-select">
                    <option value="">All genre</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre }}">{{ $genre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="shop-container__form-group">
                <label for="shop_name" class="shop-container__form-label shop-container__form-label--shop-name"></label>
                <button type="submit" class="shop-container__search-button">
                    <i class="fas fa-search"></i>
                </button>
                <input type="text" name="shop_name" id="shop_name" placeholder="Search ..." class="shop-container__form-input">
            </div>
        </form>
    </div>
    @if (isset($message))
    <div class="shop-container__alert shop-container__alert--warning">
        {{ $message }}
    </div>
    @endif
    @if ($shops->isNotEmpty())
    <div class="shop-container__wrapper">
        <div class="shop-container__shop-wrapper">
            @foreach($shops as $shop)
            <div class="shop-container__shop-item">
                <div class="shop-container__shop-img">
                    <img src="{{ $shop->photo_url }}" alt="{{ $shop->shop_name }}">
                </div>
                <div class="shop-container__shop-content">
                    <h2 class="shop-container__shop-name">{{ $shop->shop_name }}</h2>
                    <!-- 評価数を星で表示 -->
                    @if (auth()->check() && auth()->user()->hasRole('user'))
                    <div class="shop-container__shop-rating">
                        @if ($shop->reviews->count() > 0)
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=$shop->reviews->avg('rating'))
                            <i class="fas fa-star"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                            @endfor
                            {{ number_format($shop->reviews->avg('rating'), 1) }}
                            ({{ $shop->reviews->count() }}件)
                            @else
                            <span>評価なし</span>
                            @endif
                    </div>
                    @endif
                    <div class="shop-container__shop-tag">
                        <p class="shop-container__shop-area">#{{ $shop->area->name }}</p> <!-- エリア名を表示 -->
                        <p class="shop-container__shop-genre">#{{ $shop->genre->name }}</p> <!-- ジャンル名を表示 -->
                    </div>
                    <div class="shop-container__shop-details-with-favorite">
                        <a href="{{ route('shops.detail', ['id' => $shop->id]) }}" class="shop-container__detail-link">詳しくみる</a>
                        <form action="{{ route('favorite.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <button type="submit" class="shop-container__favorite-button
                            @auth
                                {{ Auth::user()->favorites()->where('shop_id', $shop->id)->exists() ? 'shop-container__favorite-button--red' : 'shop-container__favorite-button--gray' }}
                            @else
                                shop-container__favorite-button--gray
                            @endauth">
                                <i class="fas fa-heart"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection