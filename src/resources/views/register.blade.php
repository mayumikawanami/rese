@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css')}}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">Registration</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="{{ route('register.post') }}" method="post" novalidate>
            @csrf
            <div class="register-form__group">
                <div class="auth-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <input class="register-form__input" type="text" name="name" id="name" placeholder="Username" value="{{ old('name') }}">
                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <div class="auth-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input class="register-form__input" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                <p class="register-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <div class="auth-icon_key"></div>
                <input class="register-form__input" type="password" name="password" id="password" placeholder="Password">
                <p class="register-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="register-form__btn btn" type="submit" value="登録">
        </form>
    </div>
</div>
@endsection('content')