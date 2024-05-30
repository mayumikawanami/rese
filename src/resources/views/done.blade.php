@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css')}}">
@endsection

@section('content')
<div class="done-container">
    <h2 class="done-heading">ご予約ありがとうございます</h2>
    <button onclick="window.location='{{ url()->previous() }}'" class="back-button">戻る</button>
</div>
@endsection