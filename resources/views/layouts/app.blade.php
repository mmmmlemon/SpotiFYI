<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- HEAD --}}
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Заголовок веб-сайта --}}
    <title>{{ config('settings')->site_title }}</title>

    <!-- JS Скрипты -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/halfmoon.min.js') }}" defer></script>
    <script src="{{ asset('js/fontawesome.min.js') }}" defer></script>

    <!-- Шрифты -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- CSS Стили -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/halfmoon-variables.css') }}" rel="stylesheet">

</head>

{{-- BODY --}}
<body class="dark-mode">

    <div class="container-sm">

        <div id="app" class="page-wrapper with-navbar">

            <nav class="navbar">
            
            <div class="navbar-content">
                <img src="/logo.png" width="50px" style="margin-right:10px; padding:5px;" alt="">
            </div>
    
            <router-link to="/"><a class="navbar-brand">
                {{config('settings')->site_title}}
            </a></router-link>
    
            <span class="navbar-text text-monospace">v0.0</span>
    
            <ul class="navbar-nav d-none d-md-flex">
                <li class="nav-item active">
                    <router-link to="/"><a class="nav-link">Главная</a></router-link>
                </li>
                <li class="nav-item active">
                    <router-link to="/tests"><a class="nav-link">Dev: Tests</a></router-link>
                </li>
            </ul>
            @if($access_check == false)
                <div class="form-inline d-none d-md-flex ml-auto"> 
                    <a href="/spotify_auth" class="btn btn-primary btn-rounded">Войти в Spotify</a>
                </div>
            @else
                <div class="form-inline d-none d-md-flex ml-auto" style="margin-right:3%;"> 
                        {{-- юзернейм --}}
                        <router-link to="/spotify_profile" class=""><a class="nav-link">{{$spotify_profile['display_name']}}</a></router-link>
                        {{-- юзерпик --}}
                        <img src="{{$spotify_profile['avatar']}}" alt="Spotify avatar" class="nav_item_spotify_avatar rounded-circle">
                        {{-- кнопка выхода --}}
                        <a class="nav-link" href="/spotify_logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            @endif
            
            {{-- Меню для мобилок --}}
            <div class="navbar-content d-md-none ml-auto"> 
    
                <div class="dropdown with-arrow">
                
                <button class="btn" data-toggle="dropdown" type="button" id="navbar-dropdown-toggle-btn-1">
                    Меню
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </button>
    
                <div class="dropdown-menu dropdown-menu-right w-200" aria-labelledby="navbar-dropdown-toggle-btn-1">
                    <router-link to="/"><a class="dropdown-item">Главная</a></router-link>
                    <router-link to="/tests"><a class="dropdown-item">Dev:Tests</a></router-link>
                    <div class="dropdown-divider"></div>
                    
                    @if($access_check == false)
                    <div class="dropdown-content">
                        <a href="/spotify_auth" class="btn btn-primary btn-block btn-rounded">Войти в Spotify</a>
                    </div>    
                    @else
                    <div class="dropdown-content">
                        {{-- юзернейм --}}
                        <router-link to="/spotify_profile" class="">{{$spotify_profile['display_name']}}</a></router-link>
                        {{-- кнопка выхода --}}
                        <a href="/spotify_logout" class="" style="margin-left:20px;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>  
                    @endif
                </div>
                
                </div>
    
            </div>
    
            </nav>
        
            <!-- Content wrapper -->
            <div class="content-wrapper" style="padding-left:2%; padding-right:2%;">
            <div class="container" >
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
            </div>
    
        </div>
    </div>


</body>

</html>
