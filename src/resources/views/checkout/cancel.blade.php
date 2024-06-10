@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/checkout/cancel.css')}}">
@endsection

@section('content')
<div class="cancel-container">
    <h1 class="cancel-container__head">お支払いがキャンセルされました</h1>
    <p class="cancel-container__status">お支払いがキャンセルされました。ご質問がある場合は、サポートチームまでお問い合わせください。</p>
    <a href="{{ url('/') }}" class="btn btn-primary">ホームに戻る</a>
</div>
@endsection