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
    <script src="{{ asset('js/trumbowyg/trumbowyg.noembed.js') }}"></script>
    <script src="{{ asset('js/trumbowyg/trumbowyg.cleanpaste.js') }}"></script>
    <script src="{{ asset('js/charCounter.min.js') }}"></script>
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
<body class="dark-mode">
           <!-- враппер -->
           <div class="content-wrapper" style="padding-left:2%; padding-right:2%;">
            <div class="container" >
                {{-- контент --}}
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
</body>
</html>