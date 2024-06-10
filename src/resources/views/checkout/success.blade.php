@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/checkout/success.css')}}">
@endsection

@section('content')
<div class="success-container">
    <h1 class="success-container__head">お支払いが完了しました</h1>
    <p class="success-container__status">お支払いありがとうございます！お取引が正常に完了しました。</p>
    <a href="{{ url('/') }}" class="btn btn-primary">ホームに戻る</a>
</div>
@endsection