@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/shops.css')}}">
@endsection

@section('content')
<div class="shops-container">
    @if (session('status'))
    <div class="shops__alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="store-information__creation">
        <h2 class="shops-container__title">店舗情報作成</h2>
        <form action="{{ route('shopManager.storeShop') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Shop</label>
                <input type="text" name="shop_name" class="form-control" placeholder="店名を入力してください" required>
            </div>
            <div class="form-group">
                <label for="area">area</label>
                <input type="text" name="area" class="form-control" placeholder="エリアを入力してください" required>
            </div>
            <div class="form-group">
                <label for="genre">genre</label>
                <select name="genre" class="form-control" required>
                    <option value="">ジャンルを選択してください</option>
                    <option value="居酒屋">居酒屋</option>
                    <option value="寿司">寿司</option>
                    <option value="焼肉">焼肉</option>
                    <option value="焼肉">イタリアン</option>
                    <option value="焼肉">ラーメン</option>
                </select>
            </div>
            <div class="form-group">
                <label for="info">info</label>
                <textarea name="info" class="form-control" rows="5" placeholder="店舗概要を入力してください" required></textarea>
            </div>
            <div class="form-group">
                <label for="photo_url">画像URL</label>
                <select name="photo_url" class="form-control" required>
                    <option value="">画像URLを選択してください</option>
                    <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/sushi.jpg">寿司</option>
                    <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/yakiniku.jpg">焼肉</option>
                    <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/izakaya.jpg">居酒屋</option>
                    <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/italian.jpg">イタリアン</option>
                    <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/ramen.jpg">ラーメン</option>
                </select>
            </div>
            <div class="shops-button">
                <button type="submit" class="primary-button">店舗を作成する</button>
                <a class="back-button" href="{{ route('shopManager.dashboard') }}">戻る</a>
            </div>
        </form>
    </div>
    <div class="store-information__update">
        <h2 class="shops-container__title">店舗情報の更新</h2>
        <ul class="shop-container__edit-shops">
            @foreach($shops as $shop)
            <li class="edit-shop__list">
                <a href="{{ route('shopManager.edit_shop', ['id' => $shop->id]) }}" class="btn btn-primary">{{ $shop->shop_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection