@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css')}}">
@endsection

@section('content')
<div class=verify_email-container>
    <h1 class=verify_email-heading>メールアドレスの確認</h1>
    <div class="verify_email-content">
        <p class="verify_email-message">新しい確認リンクがメールアドレスに送信されました。</p>
        <p class="verify_email-message">次に進む前に、確認リンクが記載されたメールをチェックしてください。</p>
        <div class="button-container">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">確認メールを再送信</button>
            </form>
        </div>
    </div>
@endsection