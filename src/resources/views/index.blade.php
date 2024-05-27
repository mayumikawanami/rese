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
                    <div class="shop-container__shop-tag">
                        <p class="shop-container__shop-area">#{{ $shop->area }}</p>
                        <p class="shop-container__shop-genre">#{{ $shop->genre }}</p>
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