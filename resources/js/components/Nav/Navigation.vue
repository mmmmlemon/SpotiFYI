//Navigation
//навигация
<template>
    <div class="nav siteNavigation">
        <nav class="navbar">
            <!-- название сайта -->
            <router-link to="/">
                <a class="navbar-brand siteTitle">
                    <!-- {{$siteInfo['siteTitle']}} -->
                    ShowMySpotify
                    <b class="beta">&nbsp;Beta</b>
                </a>
            </router-link>
            
            <!-- навигация для больших экранов -->
            <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item active">
                    <router-link to="/"><a class="nav-link">Главная</a></router-link>
                </li>
                <li class="nav-item active">
                    <router-link to="/about"><a class="nav-link">О проекте</a></router-link>
                </li>
                <!-- если пользователь залогинен-->
                <!-- @if($checkToken != false) -->
                    <li class="nav-item active">
                        <router-link to="/profile"><a class="nav-link">Мой профиль</a></router-link>
                    </li>
                    <li class="nav-item active">
                        <router-link to="/recentTracks"><a class="nav-link">Последние треки</a></router-link>
                    </li>
                <!-- @endif -->
                <!-- тесты -->
                <!-- <li class="nav-item active">
                    <router-link to="/tests"><a class="nav-link">Dev: Tests</a></router-link>
                </li> -->
            </ul>
        </nav>
    </div>
</template>
<script>
 export default {
     
 }
</script>
