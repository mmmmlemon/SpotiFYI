//Navigation
//навигация
<template>
    <div class="nav fadeInAnimSlow siteNavigation">
        <nav class="navbar">
            <!-- название сайта -->
            <router-link to="/">
                <a class="navbar-brand siteTitle">
                    <!-- {{$siteInfo['siteTitle']}} -->
                    {{settings['site_title']}}
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
                <li v-if="settings['checkToken'] === true" class="nav-item active">
                    <router-link to="/profile"><a class="nav-link">Мой профиль</a></router-link>
                </li>
                <li v-if="settings['checkToken'] === true" class="nav-item active">
                    <router-link to="/recentTracks"><a class="nav-link">Последние треки</a></router-link>
                </li>
                <!-- тесты -->
                <!-- <li class="nav-item active">
                    <router-link to="/tests"><a class="nav-link">Dev: Tests</a></router-link>
                </li> -->
            </ul>

            
            <!-- если пользователь незалогинен -->
            <!-- кнопка входа на сайт -->
            <div v-if="settings['checkToken'] === false" class="form-inline d-none d-lg-flex ml-auto"> 
                <a href="/spotify_login" class="btn btn-primary-n btn-rounded">Войти через Spotify</a>
            </div>
            <!-- если залогинен -->
            <div v-else class="form-inline d-none d-lg-flex ml-auto" style="margin-right:1%;"> 
                    <!-- юзернейм -->
                    <router-link to="/profile" class="">
                        <a class="nav-link">{{settings['spotifyProfile']['displayName']}}</a>
                    </router-link>
                    <!-- юзерпик -->
                    <router-link to="/profile">
                        <img :src="settings['spotifyProfile']['avatar']" alt="Spotify avatar" class="navSpotifyAvatar rounded-circle">
                    </router-link>
                    <!-- кнопка выхода -->
                    <a class="nav-link" href="/spotify_logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
            </div>

            <!-- Меню для мобилок -->
            <div class="navbar-content d-lg-none ml-auto"> 
                <div class="dropdown with-arrow">
                    <!-- кнопка меню -->
                    <button class="btn" data-toggle="dropdown" type="button" id="navbar-dropdown-toggle-btn-1">
                        Меню
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                    <!-- дропдаун -->
                    <div class="dropdown-menu dropdown-menu-right w-200" aria-labelledby="navbar-dropdown-toggle-btn-1">
                        <router-link to="/">
                            <a class="dropdown-item">Главная</a>
                        </router-link>
                        <router-link to="/about">
                            <a class="dropdown-item">О проекте</a>
                        </router-link>
                        <!-- если пользователь залогинен -->
                        <router-link v-if="settings['checkToken'] != false" to="/profile">
                            <a class="dropdown-item">Мой профиль</a>
                        </router-link>
                        <router-link v-if="settings['checkToken'] != false" to="/recentTracks">
                            <a class="dropdown-item">Последние треки</a>
                        </router-link>
                        <!-- тесты -->
                        <!--<router-link to="/tests">
                            <a class="dropdown-item">Dev:Tests</a>
                        </router-link> -->

                        <div class="dropdown-divider"></div>
                        
                        <!-- если пользователь незалогинен -->
                        <div v-if="settings['checkToken'] == false" class="dropdown-content">
                            <a href="/spotify_login" class="btn btn-primary-n btn-block btn-rounded">Войти через Spotify</a>
                        </div>    
                        <div v-else class="dropdown-content">
                            <!-- юзернейм -->
                            <router-link to="/profile" class="">
                                {{settings['spotifyProfile']['displayName']}}
                            </router-link>
                            <!-- кнопка выхода -->
                            <a href="/spotify_logout" class="" style="margin-left:20px;">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>  
                    </div>
                </div> 
            </div>

        </nav>
    </div>
</template>
<script>
 export default {



     computed: {
         settings: function(){
             return this.$store.state.homePage.navSettings;
         }
     }
 }
</script>
