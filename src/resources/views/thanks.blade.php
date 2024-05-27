@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css')}}">
@endsection

@section('content')
<div class="thanks-container">
    <h2 class="thanks-heading">会員登録ありがとうございます</h2>
    <div class="thanks-content">
        <p class="thanks-message">登録確認のためのメールを送信しました。</p>
        <p class="thanks-message">メールに記載された確認リンクをクリックして、登録を完了させてください。</p>
        <p class="thanks-message">メールが届かない場合は、以下のボタンをクリックして確認メールを再送信してください。</p>
        <div class="button-container">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">確認メールを再送信</button>
            </form>
        </div>
        @if(session('message'))
        <div class="flash_message">
            {{ session('message') }}
        </div>
        @endif
    </div>
</div>
@endsection