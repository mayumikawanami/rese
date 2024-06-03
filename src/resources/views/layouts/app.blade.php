<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('css')
</head>

<body>
    <div class="app">
        <div class="header">
            <label for="modal-toggle" class="header__menu-button">
                <i class="fas fa-bars"></i>
            </label>
            <h1 class="header__heading">Rese</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <input type="checkbox" id="modal-toggle">
    <div class="modal">
        <div class="modal__inner">
            @if(!Auth::check() )
            <ul class="modal__list">
                <li class="modal__list-item"><a href="/">Home</a></li>
                <li class="modal__list-item"><a href="/register">Registration</a></li>
                <li class="modal__list-item"><a href="/login">Login</a></li>
            </ul>
            @else
            <ul class="modal__list">
                <li class="modal__list-item"><a href="/">Home</a></li>
                <form class="modal__list-item" action="/logout" method="post">
                    @csrf
                    <button class="logout-button" type="submit">Logout</button>
                </form>
                <li class="modal__list-item"><a href="/mypage">Mypage</a></li>
                @if(Auth::user()->hasRole('shop_manager'))
                <li class="modal__list-item"><a href="/shop-manager">Shop Manager page</a></li>
                @endif
                @if(Auth::user()->hasRole('admin'))
                <li class="modal__list-item"><a href="/admin">admin page</a></li>
                @endif
            </ul>
            @endif
            <label for="modal-toggle" class="modal__close-btn">×</label>
        </div>
    </div>
</body>

</html>