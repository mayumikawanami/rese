@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css')}}">
@endsection

@section('content')
<div class="admin-container">
    <h2 class="admin-container__title">店舗管理者作成フォーム</h2>
    <div class=admin-container__form>
        <form action="{{ route('admin.createShopManager') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">パスワード確認</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">店舗管理者を作成</button>
        </form>
        @if (session('status'))
        <div class="admin__alert-success">
            {{ session('status') }}
        </div>
        @endif
    </div>
</div>
@endsection