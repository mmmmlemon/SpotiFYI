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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('{{ asset('js/jquery-3.4.1.min.js') }}')</script>
    <script src="{{ asset('js/trumbowyg/trumbowyg.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/trumbowyg/ui/trumbowyg.min.css') }}">

    <!-- Шрифты -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- CSS Стили -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('css/halfmoon-variables.css') }}" rel="stylesheet">

</head>
<body>
    {{-- #app --}}
    <div class="page-wrapper with-navbar">
        <nav class="navbar">
            {{-- логотип --}}
            <div class="navbar-content">
                <img src="{{asset($siteInfo['siteLogo'])}}" width="50px" style="margin-right:10px; padding:5px;" alt="">
            </div>

            <a href="/superuser/control_panel" class="adminLink">
                <b>Control Panel</b>
            </a>

            {{-- навигация для больших экранов --}}
            <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item active">
                    <a href="/" class="nav-link adminLink">На сайт</a>
                </li>
                <li class="nav-item active">
                    <a href="/superuser/control_panel" class="nav-link adminLink">Общие настройки</a>
                </li>
                <li class="nav-item active">
                    <a href="/superuser/control_panel/logo_and_images" class="nav-link adminLink">Лого и изображения</a>
                </li>
                <li class="nav-item active">
                    <a href="/superuser/control_panel/site_info" class="nav-link adminLink">О проекте</a>
                </li>
                <li class="nav-item active">
                    <a href="/superuser/control_panel/faq" class="nav-link adminLink">FAQ</a>
                </li>
            </ul>


                {{-- если залогинен --}}
                <div class="form-inline d-none d-lg-flex ml-auto" style="margin-right:1%;"> 
                        {{-- юзернейм --}}
                        <a class="nav-link adminLink">Admin</a>
                        {{-- кнопка выхода --}}
                        <div>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                 <i class="fas fa-sign-out-alt adminLink"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                </div>

        
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
                            <a href="/" class="dropdown-item adminLink">На сайт</a>
                            <a href="/superuser/control_panel" class="dropdown-item adminLink">Общие настройки</a>
                            <a href="/superuser/control_panel/logo_and_images" class="dropdown-item adminLink">Лого и изображения</a>
                            <a href="/superuser/control_panel/site_info" class="dropdown-item adminLink">О проект</a>
                            <a href="/superuser/control_panel/faq" class="dropdown-item adminLink">FAQ</a>
                        <div class="dropdown-divider"></div>

                        <div class="dropdown-content adminLink">
                            {{-- юзернейм --}}
                            <b>Admin</b>
                            {{-- кнопка выхода --}}
                            <a href="/logout" class="" style="margin-left:20px;">
                                <i class="fas fa-sign-out-alt adminLink"></i>
                            </a>
                        </div>  
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
    @stack('scripts')
</body>
</html>

