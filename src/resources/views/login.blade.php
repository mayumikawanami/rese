@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css')}}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading">Login</h2>
    <div class="login-form__inner">
        <form class="login-form__form" action="{{ route('login.post') }}" method="post" novalidate>
            @csrf
            <div class="login-form__group">
                <div class="auth-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input class="login-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                <p class="login-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <div class="auth-icon_key"></div>
                <input class="login-form__input" type="password" name="password" id="password" placeholder="Password">
                <p class="login-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="login-form__btn btn" type="submit" value="ログイン">
        </form>
    </div>
</div>
@endsection('content')