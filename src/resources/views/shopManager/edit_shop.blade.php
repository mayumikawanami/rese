@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shopManager/edit_shop.css')}}">
@endsection

@section('content')
<div class="edit-shop__container">
    @if (session('status'))
    <div class="edit-shop__alert-success">
        {{ session('status') }}
    </div>
    @endif
    <h2 class="edit-shop__container-title">店舗情報の更新</h2>
    <form action="{{ route('shopManager.updateShop', $shop->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Shop</label>
            <input type="text" name="shop_name" class="form-control" value="{{ $shop->shop_name }}" required>
        </div>
        <div class="form-group">
            <label for="area">area</label>
            <select name="area" class="form-control" placeholder="エリアを入力してください" required>
                @foreach($areas as $id => $name)
                <option value="{{ $name }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="genre">genre</label>
            <select name="genre" class="form-control" required>
                @foreach($genres as $id => $name)
                <option value="{{ $name }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="info">info</label>
            <textarea name="info" class="form-control" rows="5" required>{{ $shop->info }}</textarea>
        </div>
        <div class="form-group">
            <label for="photo_url">画像URL</label>
            <select name="photo_url" class="form-control" required>
                <option value="{{ $shop->photo_url }}">変更しない</option>
                <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/sushi.jpg">寿司</option>
                <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/yakiniku.jpg">焼肉</option>
                <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/izakaya.jpg">居酒屋</option>
                <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/italian.jpg">イタリアン</option>
                <option value="https://rese-aws-bucket.s3.ap-northeast-1.amazonaws.com/image/ramen.jpg">ラーメン</option>
            </select>
        </div>
        <div class="edit-shop__button">
            <button type="submit" class="primary-button">更新する</button>
            <a class="back-button" href="/shop-manager/shops">戻る</a>
        </div>
    </form>
</div>
@endsection