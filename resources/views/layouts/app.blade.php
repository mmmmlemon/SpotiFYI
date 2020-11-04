<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('settings')->site_title }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <img src="/logo.png" width="50px" style="margin-right: 10px;" alt="">
                <router-link to="/">
                    <a class="navbar-brand" style="font-size:20pt; color: #0077B9;">
                        {{ config('settings')->site_title }}
                    </a>
                </router-link>
            
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li style="margin-right: 10px;">
                            <router-link to="/" class="nav_item"><i class="fa fa-dashboard"></i>Главная</router-link>
                        </li>
                        <li style="margin-right: 10px;">
                            <router-link to="/tests" class="nav_item"><i class="fa fa-dashboard"></i>Dev: Tests</router-link>
                        </li>
                    </ul>

                    <!-- Правая сторона навигации -->
                    <ul class="navbar-nav ml-auto">
                        {{-- Авторизация через Spotify --}}
                        @if($access_check == false)
                            <li style="margin-right: 10px;">
                                <a href="/spotify_auth" class="nav_item_login_spotify">Войти в Spotify</a>
                            </li>
                        @else
                            {{-- юзернейм --}}
                            <router-link to="/spotify_profile" class="nav_item_username">{{$spotify_profile['display_name']}}</a></router-link>
                            {{-- юзерпик --}}
                            <img src="{{$spotify_profile['avatar']}}" alt="Spotify avatar" class="nav_item_spotify_avatar rounded-circle">
                            {{-- кнопка выхода --}}
                            <a href="/spotify_logout" class="nav_item_logout">Выйти</a>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
