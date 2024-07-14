@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css')}}">
@endsection

@section('content')
@if (session('status'))
<div class="admin__alert-success">
    {{ session('status') }}
</div>
@endif
@if ($errors->has('csv_errors'))
<div class="admin__alert-error">
    <ul>
        @foreach ($errors->get('csv_errors') as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if ($errors->has('message'))
<div class="admin__alert-error">
    <ul>
        <li>{{ $errors->first('message') }}</li>
    </ul>
</div>
@endif
<div class="admin-container">
    <div class=admin-container__form>
        <h2 class="admin-container__title">店舗管理者の作成</h2>
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
    </div>
    <div class="admin-container__form">
        <h2 class="admin-container__title">csvインポート</h2>
        <form action="{{ route('admin.importCsv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group__csv">
                <label for="csv_file">CSVファイルを選択してください</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">CSVをインポートする</button>
        </form>
    </div>
</div>
@endsection