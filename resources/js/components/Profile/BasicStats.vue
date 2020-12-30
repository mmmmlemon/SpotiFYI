<template>
   <div>
        <div>
            <div class="row justify-content-center">
                <div class="col-12" v-if="spotifyUserLibrary == -1">
                    <Loader />
                    <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == -1">Загружаю библиотеку пользователя...</h6>
                    <h6 class="text-center blinking_anim" v-if="spotifyUserLibrary == true">Анализирую треки...</h6>
                    <p class="font_10pt text-center">Это может занять около минуты</p>
                </div>
                <div v-else-if="spotifyUserLibrary != -1" class="row justify-content-center">
                    <div class="col-md-12 fade_in_slow_anim">
                        <h5 class="text-center">
                            <b>Общая статистика</b>&nbsp;
                            <i class="fas fa-chart-bar primary_color"></i>
                        </h5>
                    </div>
                    <!-- навигация -->
                    <div class="row justify-content-center font_10pt fade_in_anim">
                        <nav class="justify-content-center">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#basic">Общее</a></li>
                                <li class="breadcrumb-item"><a href="#tracks">Самые длинные и короткие треки</a></li>
                                <li class="breadcrumb-item"><a href="#genres">Жанры и годы</a></li>
                            </ul>
                        </nav>
                    </div>

                    <div class="col-12 justify-content-center fade_in_anim">
                    </div>
               
                    <div class="row justify-content-center">
                        <!-- треки -->
                        <LastFive :itemCount="spotifyTracks['count']" :lastFiveItems="spotifyTracks['last_five']" type="tracks" id="basic"/>  
                        <!-- альбомы -->
                        <LastFive v-if="spotifyTracks !== false && spotifyTracks !== -1" 
                                    :itemCount="spotifyAlbums['count']" :lastFiveItems="spotifyAlbums['last_five']" type="albums"/>  
                        <!-- исполнители -->
                        <LastFive v-if="spotifyAlbums !== false && spotifyAlbums !== -1" 
                                    :itemCount="spotifyArtists['count']" :lastFiveItems="spotifyArtists['random_five']" type="artists"/>  

                        <!-- часы и время -->
                        <HoursAndMinutes v-if="spotifyArtists !== -1 && spotifyAlbums != -1 && spotifyTracks != -1" 
                                        class="fade_in_slow_anim" :userLibraryTime="userLibraryTime"/>

                        <!-- самые длинные и короткие треки -->
                        <LongestAndShortest v-if="userLibraryTime !== -1" id="tracks"
                                            :fiveLongest="fiveTracks['fiveLongest']" 
                                            :fiveShortest="fiveTracks['fiveShortest']" :tracksMode="tracksMode"/>
                        <!-- любимые жанры -->
                        <FavoriteGenres v-if="fiveTracks !== -1" :favoriteGenres="favoriteGenres" id="genres"/>

                        <!-- кол-во исполнителей -->
                        <ArtistsCount v-if="favoriteGenres != -1" :uniqueArtists="uniqueArtists"/>

                        <!-- года и десятилетия -->
                        <YearsAndDecades v-if="uniqueArtists != -1" :yearsAndDecades="yearsAndDecades" type="alltime"/>

                        <!-- года и десятилетия за месяц-->
                        <YearsAndDecades v-if="yearsAndDecades != -1" :yearsAndDecades="yearsAndDecadesMonth" type="month"/>

                    </div>     
                </div>
            </div>
            <br>
            <div class="row justify-content-center fade_in_anim" v-if="yearsAndDecades != -1">
                <router-link to="/profile/top10"><button class="btn btn-primary">Перейти к "Топ-10"</button></router-link>
                <br><br>
            </div>
        </div>
   </div>
</template>

<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab', 'basicStats');

        //получаем библиотеку пользователя и статистику
        if(this.spotifyUserLibrary == -1)
        { this.$store.dispatch('getSpotifyUserLibrary'); }

        //треки, альбомы и подписки
        if(this.spotifyTracks == -1)
        { this.$store.dispatch('getSpotifyTracks'); }
        

        if(this.spotifyAlbums == -1)
        { this.$store.dispatch('getSpotifyAlbums'); }
        
        if(this.spotifyArtists == -1)
        { this.$store.dispatch('getSpotifyArtists'); }
        
        //время
        if(this.userLibraryTime == -1)
        { this.$store.dispatch('getUserLibraryTime'); }

        //пять самых длинных и коротких треков
        if(this.fiveTracks == -1)
        { this.$store.dispatch('getFiveLongestAndShortestTracks'); }

        //средняя длина трека
        if(this.tracksMode == -1)
        { this.$store.dispatch('getAverageLengthOfTrack'); }

        //жанры
        if(this.favoriteGenres == -1)
        { this.$store.dispatch('getFavoriteGenres'); }
        
        //кол-во исполнителей
        if(this.uniqueArtists == -1)
        { this.$store.dispatch('getUniqueArtists'); }
        
        //года и десятилетия
        if(this.yearsAndDecades == -1)
        { this.$store.dispatch('getYearsAndDecades'); }   

        //года и десятилетия за месяц
        if(this.yearsAndDecadesMonth == -1)
        { this.$store.dispatch('getYearsAndDecadesMonth'); }   


        
    },
    computed: {
        //библиотека пользователя
        //принимает либо true, либо false, если true - то библиотека загружена, false - ошибка, -1 - загружается
        spotifyUserLibrary: function() {
            return this.$store.state.profilePage.spotifyUserLibrary;
        },
        //кол-во треков и последние пять
        spotifyTracks: function() {
            return this.$store.state.profilePage.spotifyTracks;
        },
        //кол-во альбомов и последние пять
        spotifyAlbums: function() {
            return this.$store.state.profilePage.spotifyAlbums;
        },
        //кол-во подписок и случайные пять
        spotifyArtists: function(){
            return this.$store.state.profilePage.spotifyArtists;
        },
        //время
        userLibraryTime: function(){
            return this.$store.state.profilePage.userLibraryTime;
        },
        //пять самых длинных
        fiveTracks: function(){
            return this.$store.state.profilePage.fiveTracks;
        },
        //средняя длина трека
        tracksMode: function() {
            return this.$store.state.profilePage.tracksMode;
        },
        //любимые жанры
        favoriteGenres: function(){
            return this.$store.state.profilePage.favoriteGenres;
        },
        //кол-во исполнителей
        uniqueArtists: function(){
            return this.$store.state.profilePage.uniqueArtists;
        },
        //года и десятилетия
        yearsAndDecades: function(){
            return this.$store.state.profilePage.yearsAndDecades;
        },
        //года и десятилетия
        yearsAndDecadesMonth: function(){
            return this.$store.state.profilePage.yearsAndDecadesMonth;
        },
    }
}
</script>