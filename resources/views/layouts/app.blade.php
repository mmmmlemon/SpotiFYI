<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- HEAD --}}
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Заголовок веб-сайта --}}
    <title>{{ config('settings')->site_title }} Beta</title>

    <!-- JS Скрипты -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/halfmoon.min.js') }}" defer></script>
    <script src="{{ asset('js/fontawesome.min.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.2.4/gsap.min.js"></script>

    <!-- Шрифты -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;800&display=swap" rel="stylesheet">

    <!-- CSS Стили -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('css/halfmoon-variables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/override.css') }}" rel="stylesheet">

    <link rel="manifest" href="manifest.json">

{{-- <meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="SpotiFYI">
<meta name="apple-mobile-web-app-title" content="SpotiFYI">
<meta name="theme-color" content="#1b77b9">
<meta name="msapplication-navbutton-color" content="#1b77b9">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="msapplication-starturl" content="/public_html/index.php">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="icon" type="image/png" sizes="32x32" href="/icon.png">
<link rel="apple-touch-icon" type="image/png" sizes="32x32" href="/icon.png"> --}}

</head>

{{-- BODY --}}
<body class="dark-mode">
        {{-- #app --}}
        <div id="app" class="page-wrapper with-navbar">
            <nav class="navbar">
                {{-- логотип --}}
                <div class="navbar-content">
                    <img src="{{asset($siteInfo['siteLogo'])}}" width="50px" style="margin-right:10px; padding:5px;" alt="">
                </div>

                {{-- название сайта --}}
                <router-link to="/"><a class="navbar-brand siteTitle">
                    {{$siteInfo['siteTitle']}}
                    <b class="beta">&nbsp;Beta</b>
                </a></router-link>

                {{-- навигация для больших экранов --}}
                <ul class="navbar-nav d-none d-lg-flex">
                    <li class="nav-item active">
                        <router-link to="/"><a class="nav-link">Главная</a></router-link>
                    </li>
                    <li class="nav-item active">
                        <router-link to="/about"><a class="nav-link">О проекте</a></router-link>
                    </li>
                    {{-- если пользователь залогинен --}}
                    @if($checkToken != false)
                        <li class="nav-item active">
                            <router-link to="/profile"><a class="nav-link">Мой профиль</a></router-link>
                        </li>
                        <li class="nav-item active">
                            <router-link to="/recentTracks"><a class="nav-link">Последние треки</a></router-link>
                        </li>
                    @endif
                    {{-- тесты --}}
                    {{-- <li class="nav-item active">
                        <router-link to="/tests"><a class="nav-link">Dev: Tests</a></router-link>
                    </li> --}}
                </ul>

                {{-- если пользователь незалогинен --}}
                @if($checkToken == false)
                    {{-- кнопка входа на сайт --}}
                    <div class="form-inline d-none d-lg-flex ml-auto"> 
                        <a href="/spotify_login" class="btn btn-primary-n btn-rounded">Войти через Spotify</a>
                    </div>
                @else
                    {{-- если залогинен --}}
                    <div class="form-inline d-none d-lg-flex ml-auto" style="margin-right:1%;"> 
                            {{-- юзернейм --}}
                            <router-link to="/profile" class="">
                                <a class="nav-link">{{$spotifyProfile['displayName']}}</a>
                            </router-link>
                            {{-- юзерпик --}}
                            <router-link to="/profile">
                                <img src="{{$spotifyProfile['avatar']}}" alt="Spotify avatar" class="navSpotifyAvatar rounded-circle">
                            </router-link>
                            {{-- кнопка выхода --}}
                            <a class="nav-link" href="/spotify_logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                    </div>
                @endif
            
                {{-- Меню для мобилок --}}
                <div class="navbar-content d-lg-none ml-auto"> 
                    <div class="dropdown with-arrow">
                        {{-- кнопка меню --}}
                        <button class="btn" data-toggle="dropdown" type="button" id="navbar-dropdown-toggle-btn-1">
                            Меню
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        {{-- дропдаун --}}
                        <div class="dropdown-menu dropdown-menu-right w-200" aria-labelledby="navbar-dropdown-toggle-btn-1">
                            <router-link to="/">
                                <a class="dropdown-item">Главная</a>
                            </router-link>
                            <router-link to="/about">
                                <a class="dropdown-item">О проекте</a>
                            </router-link>
                            {{-- если пользователь залогинен --}}
                            @if($checkToken != false)
                                <router-link to="/profile">
                                    <a class="dropdown-item">Мой профиль</a>
                                </router-link>
                                <router-link to="/recentTracks">
                                    <a class="dropdown-item">Последние треки</a>
                                </router-link>
                            @endif
                            {{-- тесты --}}
                            {{-- <router-link to="/tests">
                                <a class="dropdown-item">Dev:Tests</a>
                            </router-link> --}}

                            <div class="dropdown-divider"></div>
                            
                            {{-- если пользователь незалогинен --}}
                            @if($checkToken == false)
                                <div class="dropdown-content">
                                    <a href="/spotify_login" class="btn btn-primary-n btn-block btn-rounded">Войти через Spotify</a>
                                </div>    
                            @else
                            <div class="dropdown-content">
                                {{-- юзернейм --}}
                                <router-link to="/profile" class="">
                                    {{$spotifyProfile['displayName']}}
                                </router-link>
                                {{-- кнопка выхода --}}
                                <a href="/spotify_logout" class="" style="margin-left:20px;">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>  
                            @endif
                        </div>
                    </div> 
                </div>
            </nav>
        
            <!-- враппер -->
            <div class="content-wrapper" style="padding-left:2%; padding-right:2%;">
            <div class="container" >
                {{-- контент --}}
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
            </div>
    
        </div>
        
</body>

</html>
